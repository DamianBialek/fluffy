import EmptyRouterView from "../../components/EmptyRouterView";
import ParametersList from "../../views/parameters/ParametersList";

export default {
    path: '/configuration',
    name: 'configuration',
    component: EmptyRouterView,
    meta: {
        title: 'Konfiguracja'
    },
    redirect: '/configuration/parameters/list',
    children: [
        {
            path: 'parameters/list',
            name: 'parametersList',
            component: ParametersList,
            meta: {
                title: 'Parametry'
            }
        }
    ]
}
