/**
 * WEBPACK CONFIGURATION
 */

const baseConfig = require('./webpack-base.config');

const themeVariables = require('./webpack-theme.var.config');

module.exports = function (env, argv) {
    return baseConfig.buildConfig(themeVariables, argv.mode);
};
