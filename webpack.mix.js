const _ = require('lodash');
const jsonFile = require('jsonfile');
const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs');

const assetsPath = 'resources/';
const themePath = '/frontend/';
const publicPath = 'public/frontend/';

const mixManifest = publicPath + '/mix-manifest.json';

mix.disableNotifications();

//mix.browserSync('laravel-spa.test:8081');

mix.setPublicPath(publicPath);
mix.setResourceRoot('../');

fs.readdir(path.resolve(publicPath + 'js/chunks'), (err, files) => {
    if (err) {
        console.log(err);
    }
    else {
        files.forEach(function (file) {
            fs.unlink(path.resolve(publicPath + 'js/chunks' + file), function () {
                console.log(publicPath + 'js/chunks' + file + ' - deleted');
            });
        });
    }
});


mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.json', '.jxs', '.vue'],
        alias: {
            '@': path.resolve(__dirname, assetsPath + 'js/')
        }
    },
    output: {
        publicPath : themePath,
        chunkFilename: 'js/chunks/[id].[chunkhash].js'
    }
});

mix.js(assetsPath  + 'js/app.js', publicPath + 'js')
    .postCss(assetsPath + 'css/app.css', publicPath + 'css', [
        require("tailwindcss"),
    ])
    .vue({ version: 2 })
    .version();

mix.copyDirectory(assetsPath + 'images', publicPath + 'images');

mix.then(() => {
    jsonFile.readFile(mixManifest, (err, obj) => {
        const newJson = {};
        _.forIn(obj, (value, key) => {
            const newKey = _.trimEnd(themePath, '/') + key;
            newJson[newKey] = value
        });
        jsonFile.writeFile(mixManifest, newJson, { spaces: 4 }, (err) => {
            if (err) console.error(err)
        });
    });
});


