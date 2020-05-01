const modulesFiles = require.context("./modules", true, /\.ts$/);

const moduleRoutesIsAppRouteConfig = function (route: any): route is AppRouteConfig {
    return !!(route && route.name);
}

const modules: { [key: string]: AppRouteConfig } = modulesFiles.keys().reduce((modules, modulePath) => {
    // set './app.js' => 'app'
    const moduleName = modulePath.replace(/^\.\/(.*)\.\w+$/, '$1')
    const value = modulesFiles(modulePath)

    if(!moduleRoutesIsAppRouteConfig(value.default))
        throw new Error(`Module '${moduleName}' routes must implement AppRouteConfig interface !`);

    // @ts-ignore
    modules[moduleName] = value.default
    return modules
}, {});

export default modules;
