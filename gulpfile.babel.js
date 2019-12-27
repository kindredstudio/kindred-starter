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

const PRODUCTION = yargs.argv.prod;

const server = browserSync.create();

const config = {
  domain: 'cara-co.local',
  browserList: ['> 0.2%', 'not dead'],
};

const sync = done => {
  server.init({
    proxy: config.domain,
  });
  done();
};

const reload = done => {
  server.reload();
  done();
};

const clean = () => del(['dist']);

const styles = () => {
  return src(['src/scss/bundle.scss'])
    .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(sass().on('error', sass.logError))
    .pipe(
      gulpif(
        PRODUCTION,
        postCss([
          autoprefixer({
            browserList: config.browserList,
          }),
        ])
      )
    )
    .pipe(gulpif(PRODUCTION, cleanCss({ compatibility: 'ie8' })))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(dest('dist/css'))
    .pipe(server.stream());
};

// const copy = () => {
//   return src(['src/**/*', '!src/{images,js,scss}', '!src/{images,js,scss}/**/*']).pipe(
//     dest('dist')
//   );
// };

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
        mode: PRODUCTION ? 'production' : 'development',
        devtool: !PRODUCTION ? 'inline-source-map' : false,
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
    .pipe(dest('dist/js'));
};

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
    .pipe(gulpif(file => file.relative.split('.').pop() !== 'zip', replace('_banana', info.name)))
    .pipe(zip(`${info.name}.zip`))
    .pipe(dest('bundled'));
};

const watchForChanges = () => {
  watch('src/scss/**/*.scss', styles);
  // watch(['src/**/*', '!src/{images,js,scss}', '!src/{images,js,scss}/**/*'], series(copy, reload));
  watch('src/js/**/*.js', series(scripts, reload));
  watch('**/*.php', reload);
  watch('views/*.twig', reload);
};

const dev = series(clean, parallel(styles, scripts), sync, watchForChanges);

export const build = series(clean, parallel(styles, scripts));
export const bundle = series(clean, parallel(styles, scripts), compress);

export default dev;
