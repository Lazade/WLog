// const path = require('path');

module.exports = [
    // back-end 
    {
      entry: './public/backend/styles/backend.scss',
      output: {
        filename: 'style-bundle.js',
        path: __dirname + '/public/backend/styles/'
      },
      module: {
        rules: [{
          test: /\.scss$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                name: 'backend.css',
              },
            },
            { loader: 'extract-loader' },
            { loader: 'css-loader' },
            {
              loader: 'sass-loader',
              options: {
                includePaths: ['./node_modules'],
              }
            },
          ]
        }]
      },
    },
    {
      entry: "./public/backend/scripts/backend.js",
      output: {
        filename: "backend-common.js",
        path: __dirname + '/public/backend/scripts/'
      },
      module: {
        loaders: [{
          test: /\.js$/,
          loader: 'babel-loader',
          query: {
            presets: ['env']
          }
        }]
      },
    },
    // front-end
    {
      entry: './public/app/styles/app.scss',
      output: {
        filename: 'style-bundle.js',
        path: __dirname + '/public/app/styles/'
      },
      module: {
        rules: [{
          test: /\.scss$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                name: 'app.css',
              },
            },
            { loader: 'extract-loader' },
            { loader: 'css-loader' },
            {
              loader: 'sass-loader',
              options: {
                includePaths: ['./node_modules'],
              }
            },
          ]
        }]
      },
    },
    {
      entry: "./public/app/scripts/main.js",
      output: {
        filename: "app.js",
        path: __dirname + '/public/app/scripts/'
      },
      module: {
        loaders: [{
          test: /\.js$/,
          loader: 'babel-loader',
          query: {
            presets: ['env']
          }
        }]
      }
    },
  ];
  