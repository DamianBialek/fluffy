import {RouteConfig} from "vue-router/types/router";

// vue
declare module '*.vue' {
    import Vue from "vue";
    export default Vue
}

declare global {
    interface AppRouteConfig extends RouteConfig {
        icon?: string,
        title?: string,
        name: string,
        children?: AppRouteConfig[]
    }
}
