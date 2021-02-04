import EmptyRouterView from "../../components/EmptyRouterView";
import UserEdit from "../../views/user/UserEdit";

export default {
    path: '/user',
    name: 'user',
    component: EmptyRouterView,
    meta: {
        title: 'Panel użytkownika',
        hideInSidebar: true
    },
    redirect: '/user/edit',
    children: [
        {
            path: 'list',
            name: 'userEdit',
            component: UserEdit,
            meta: {
                title: 'Edycja danych'
            }
        }
    ]
}
