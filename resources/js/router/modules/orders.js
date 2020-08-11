import EmptyRouterView from "../../components/EmptyRouterView";
import OrdersList from "../../views/orders/OrdersList";
import OrdersAdd from "../../views/orders/OrdersAdd";

export default {
    path: '/orders',
    name: 'orders',
    component: EmptyRouterView,
    meta: {
        title: 'Zlecenia',
    },
    redirect: '/orders/list',
    children: [
        {
            path: 'list',
            name: 'ordersList',
            component: OrdersList,
            meta: {
                title: 'Lista zleceń'
            }
        },
        {
            path: 'add',
            name: 'ordersAdd',
            component: OrdersAdd,
            meta: {
                title: 'Dodaj nowe zlecenie'
            }
        }
    ]
}
