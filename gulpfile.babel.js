import { src, dest, watch, series, parallel } from 'gulp';
import browserSync from 'browser-sync';
import webpack from 'webpack-stream';
import postCss from 'gulp-postcss';
import sass from 'gulp-sass';
import autoprefixer from 'autoprefixer';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import sourcemaps from 'gulp-sourcemaps';
import del from 'del';
import named from 'vinyl-named';
import zip from 'gulp-zip';
import info from './package.json';
import replace from 'gulp-replace';
import yargs from 'yargs';

const config = {
  domain: 'kindred.local',
  browserList: ['> 0.2%', 'not dead'],
};

const IS_PRODUCTION = !!yargs.argv.prod;

const server = browserSync.create();

const sync = (done) => {
  server.init({
    proxy: config.domain,
  });
  done();
};

const reload = (done) => {
  server.reload();
  done();
};

const clean = () => del(['dist']);

const styles = () => {
  return src(['src/scss/bundle.scss', 'src/scss/admin.scss'])
    .pipe(gulpif(!IS_PRODUCTION, sourcemaps.init()))
    .pipe(sass().on('error', sass.logError))
    .pipe(
      gulpif(
        IS_PRODUCTION,
        postCss([
          autoprefixer({
            browserList: config.browserList,
          }),
        ])
      )
    )
    .pipe(gulpif(IS_PRODUCTION, cleanCss()))
    .pipe(gulpif(!IS_PRODUCTION, sourcemaps.write()))
    .pipe(dest('dist'))
    .pipe(server.stream());
};

const copy = () => {
  return src(['src/**/*', '!src/{js,scss}', '!src/{js,scss}/**/*']).pipe(dest('dist'));
};

const scripts = () => {
  return src(['src/js/bundle.js'])
    .pipe(named())
    .pipe(
      webpack({
        module: {
          rules: [
            {
              test: /\.js$/,
              use: {
                loader: 'babel-loader',
                options: {
                  presets: [],
                },
              },
            },
          ],
        },
        mode: IS_PRODUCTION ? 'production' : 'development',
        devtool: !IS_PRODUCTION ? 'inline-source-map' : false,
        output: {
          filename: '[name].js',
        },
        resolve: {
          alias: {
            jquery: 'jquery',
          },
        },
      })
    )
    .pipe(dest('dist'));
};

// const images = () => {
//   return src('src/img/**/*')
//     .pipe(
//       imagemin({
//         progressive: true,
//       })
//     )
//     .pipe(dest('dist/img'));
// };

const compress = () => {
  return src([
    '**/*',
    '!node_modules{,/**}',
    '!bundled{,/**}',
    '!src{,/**}',
    '!.babelrc',
    '!.gitignore',
    '!gulpfile.babel.js',
    '!package.json',
    '!package-lock.json',
  ])
    .pipe(gulpif((file) => file.relative.split('.').pop() !== 'zip', replace('_kindred', info.name)))
    .pipe(zip(`${info.name}.zip`))
    .pipe(dest('bundled'));
};

const watchForChanges = () => {
  watch('src/scss/**/*.scss', styles);
  watch(['src/**/*', '!src/{img,js,scss}', '!src/{img,js,scss}/**/*'], series(copy, reload));
  watch('src/js/**/*.js', series(scripts, reload));
  watch('**/*.php', reload);
  watch('views/**/*.twig', reload);
};

const dev = series(clean, parallel(styles, scripts, copy), sync, watchForChanges);

export const build = series(clean, parallel(styles, scripts, copy));
export const bundle = series(clean, parallel(styles, scripts, copy), compress);

export default dev;
