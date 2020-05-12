import EmptyRouterView from "../../components/EmptyRouterView";
import MechanicsList from "../../views/mechanics/MechanicsList";
import MechanicsAddMechanic from "../../views/mechanics/MechanicsAddMechanic";

export default {
    path: '/mechanics',
    name: 'mechanics',
    component: EmptyRouterView,
    meta: {
        title: 'Mechanicy'
    },
    redirect: '/mechanics/list',
    children: [
        {
            path: 'list',
            name: 'mechanicsList',
            component: MechanicsList,
            meta: {
                title: 'Lista mechaników'
            }
        },
        {
            path: 'add',
            name: 'mechanicsAddMechanic',
            component: MechanicsAddMechanic,
            meta: {
                title: 'Dodawanie nowego mechanika'
            }
        }
    ]
}
