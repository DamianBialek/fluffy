const { resolve } = require('path');

module.exports = {
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@scss': resolve(__dirname, 'resources/sass')
        }
    }
};
