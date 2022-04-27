require('dotenv').config();
const mix = require('laravel-mix');
const { exec } = require('child_process');
const path = require('path');
const webpack = require('webpack');

mix.extend('ziggy', new class {
   register(config = {})
   {
      this.watch = config.watch ?? ['routes/**/*.php'];
      this.path = config.path ?? '';
      this.enabled = config.enabled ?? !Mix.inProduction();
   }
   boot() {
      if ( !this.enabled )
      {
         return;
      }
      const command = () => exec(`${process.env.APP_PHP_PATH} artisan ziggy:generate ${this.path}`, (error, stdout, stderr) => {
         if( error )
         {
            console.log('error: ', error);
         }
         if( stdout )
         {
            console.log(stdout);
         }
         if( stderr )
         {
            console.log('stderr: ', stderr);
         }
      });
      
      command();
      
      if( Mix.isWatching() && this.watch )
      {
         ((require('chokidar')).watch(this.watch)).on('change', (path) => {
            console.log(`${path} changed...`);
            command();
         });
      };
   }
}());


mix
   .ziggy({
      enabled: true,
      path: 'resources/js/ziggy.js'
   })
   .webpackConfig({
      stats: {
         children: false
      }
   });

mix
   .alias({
      ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue'), // or 'vendor/tightenco/ziggy/dist/vue' if you're using the Vue plugin
   })
   .options({
      processCssUrls: false,
      runtimeChunkPath: 'storage',
      autoprefixer: {
         enabled: true,
         options: {
            remove: false
         }
      },
      postCss:[
         require('autoprefixer')(),
      ],
   }).disableSuccessNotifications();

mix
   .js('resources/js/app.js', 'public/js')
   .vue()
   .version();

mix
   .sass('resources/sass/app.scss', 'public/css')
   .version();

mix
   .sass('resources/sass/categories.sass', 'public/css').version();

mix
   .styles([
      'public/css/vendor/side-bar.css',
      'public/css/vendor/stylenew2main.css',
      'public/css/vendor/SelectSearch.css',
      'public/css/vendor/swiper.css',
   ], 'public/css/main.css')
   .version();

// Обновление браузера после внесенных изменений
mix.browserSync('http://127.0.0.1:8000');
