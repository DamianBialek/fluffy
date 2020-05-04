const path = require('path');
const scssFullPath = path.resolve(__dirname, 'resources/sass');

const config = {
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@scss': scssFullPath,
            '~': path.join(__dirname, 'resources/assets/js')
        }
    },
}

module.exports = config;
