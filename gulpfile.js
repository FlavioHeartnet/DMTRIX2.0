var elixir = require('laravel-elixir'),
    livereload = require('gulp-livereload'),
    clean = require('rimraf'),
    gulp = require('gulp');

var config = {

    asset_path: './resources/assets',
    build_path: './public/build'

};

    config.bower_path = config.asset_path + '/../bower_components';
    config.build_path_js = config.build_path + '/js';
    config.build_vendor_path_js = config.build_path_js +'/vendor';
    config.vendor_path_js = [
        
        config.bower_path + '/jquery/dist/jquery.min.js', 
        config.bower_path+'/angular/angular.min.js',
        config.bower_path+'/angular-bootstrap/ui-bootstrap.min.js',
        config.bower_path+'/angular-messages/angular-messages.min.js',
        config.bower_path+'/angular-resource/angular-resource.min.js',
        config.bower_path+'/angular-route/angular-route.min.js',
        config.bower_path+'/angular-strap/modules/navbar.min.js',
        config.bower_path+'/angular-animate/angular-animate.min.js',
        config.bower_path+'/chart.js/dist/Chart.min.js',
        config.bower_path+'/jquery.gritter/js/jquery.gritter.min.js',
        config.bower_path+'/jquery.sparkline/src/base.js',
        config.bower_path+'/bootstrap/dist/js/bootstrap.min.js',
        config.bower_path+'/bootstrap-switch/dist/js/bootstrap-switch.min.js',
        config.bower_path+'/jquery.nicescroll/dist/jquery.nicescroll.min.js',
        config.bower_path+'/jquery.scrollTo/jquery.scrollTo.min.js'
        
        
        
    ];

    config.build_path_css = config.build_path + '/css';
    config.build_vendor_path_css = config.build_path_css +'/vendor';
    config.vendor_path_css = [config.bower_path + '/bootstrap/dist/css/bootstrap-theme.min.css', config.bower_path+'/bootstrap/dist/css/bootstrap.min.css',

        config.bower_path+'/jquery.gritter/css/jquery.gritter.css',
        config.bower_path+'/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css'
    
    
    
    ];

    gulp.task('copy-styles', function(){

        gulp.src([
            config.asset_path + '/css/**/*.css'
        ]).pipe(gulp.dest(config.build_path_css)).pipe(livereload());

        gulp.src(config.vendor_path_css).pipe(gulp.dest(config.build_vendor_path_css)).pipe(livereload());

    });

gulp.task('copy-scripts', function(){

    gulp.src([
        config.asset_path + '/js/**/*.js'
    ]).pipe(gulp.dest(config.build_path_js)).pipe(livereload());

    gulp.src(config.vendor_path_js).pipe(gulp.dest(config.build_vendor_path_js)).pipe(livereload());

});

gulp.task('clean-build-folder', function(){

    clean.sync(config.build_path);

});

gulp.task('watch-dev',['clean-build-folder'], function () {
    livereload.listen();
    gulp.start('copy-styles', 'copy-scripts');
    gulp.watch(config.asset_path+'./**', 'copy-styles', 'copy-scripts');

});





