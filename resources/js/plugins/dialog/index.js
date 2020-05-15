import ConfirmDialog from "./components/ConfirmDialog";
import Dialog from "./components/Dialog";

let Plugin = function(Vue) {
    this.vue = Vue;
    this.$root = {};
    this.mounted = false;
}

Plugin.prototype.mountIfNotMounted = function () {
    if (this.mounted === true) {
        return
    }

    this.$root = (() => {
        let DialogConstructor = this.vue.extend(Dialog)
        let node = document.createElement('div')
        document.querySelector('body').appendChild(node)

        let Vm = new DialogConstructor()

        return Vm.$mount(node)
    })()

    this.mounted = true
}

Plugin.prototype.confirm = function (message, options = {}) {
    const component = ConfirmDialog;

    return this.open(component, Object.assign({
        message
    }, options));
}

Plugin.prototype.open = function (component, options) {
    this.mountIfNotMounted();
    return new Promise((resolve, reject) => {
        this.$root.openNewDialog({
            component: component,
            options: Object.assign({
                resolve,
                reject
            }, options)
        })
    })
}

export default function install(Vue, options) {
    const plugin = new Plugin(Vue);

    Vue.prototype.$dialog = plugin;
    Vue.prototype.$confirm = plugin.confirm.bind(plugin);
}
