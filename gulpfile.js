var 
  gulp     = require('gulp'),
  concat   = require('gulp-concat')
;
var scriptFiles = [ 'ang/app.js' ];
var scriptDestDir  = 'web/js';
var scriptVendorFiles = 
[
  'bower_components/Blob/Blob.js',
  'bower_components/FileSaver/FileSaver.min.js',
  'bower_components/angularjs/angular.min.js',
  'bower_components/ng-file-upload/angular-file-upload.min.js'
];
var cssDestDir     = 'web/css';
var cssVendorFile  = 'vendor.css';
var cssVendorFiles = 
[
  'bower_components/bootstrap/dist/css/bootstrap.min.css'
];

var watchTask = function() 
{
  buildTask();
  
  gulp.watch(scriptFiles,['scripts' ]);
};
gulp.task('watch',watchTask);

var buildTask = function()
{
  cssTask();
  scriptTask();
};
gulp.task('build',buildTask);

var scriptTask = function() 
{/*
  gulp.src(scriptFiles)
    .pipe(concat('app.js'))
    .pipe(gulp.dest('web/js'));*/
    
  gulp.src(scriptVendorFiles)
    .pipe(concat('vendor.js'))
    .pipe(gulp.dest(scriptDestDir));
    
  // Map files to keep chrome happy
  var scriptVendorMapFiles = [];
  scriptVendorFiles.forEach(function(jsFile)
  {
    scriptVendorMapFiles.push(jsFile + '.map');
  });
  gulp.src(scriptVendorMapFiles)
    .pipe(gulp.dest(scriptDestDir));
};
gulp.task('script', scriptTask);

var cssTask = function() 
{/*
  gulp.src(scriptFiles)
    .pipe(concat('app.js'))
    .pipe(gulp.dest('web/js'));*/
    
  gulp.src(cssVendorFiles)
    .pipe(concat(cssVendorFile))
    .pipe(gulp.dest(cssDestDir));
    
  // Map files to keep chrome happy
  /*
  var scriptVendorMapFiles = [];
  scriptVendorFiles.forEach(function(jsFile)
  {
    scriptVendorMapFiles.push(jsFile + '.map');
  });
  gulp.src(scriptVendorMapFiles)
    .pipe(gulp.dest(scriptDest));*/
};
gulp.task('css', cssTask);