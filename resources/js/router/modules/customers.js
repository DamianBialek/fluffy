import CustomersList from "../../views/customers/CustomersList";
import CustomersAddCustomer from "../../views/customers/CustomersAddCustomer";
import CustomersEditCustomer from "../../views/customers/CustomersEditCustomer";
import EmptyRouterView from "../../components/EmptyRouterView";

export default {
    path: '/customers',
    name: 'customers',
    component: EmptyRouterView,
    meta: {
        title: 'Klienci'
    },
    redirect: '/customers/list',
    children: [
        {
            path: 'list',
            name: 'customersList',
            component: CustomersList,
            meta: {
                title: 'Lista klientów'
            }
        },
        {
            path: 'add',
            name: 'customersAddCustomer',
            component: CustomersAddCustomer,
            meta: {
                title: 'Dodawanie nowego klienta'
            }
        },
        {
            path: 'edit/:id',
            name: 'customersEditCustomer',
            component: CustomersEditCustomer,
            meta: {
                title: 'Edycja danych klienta'
            }
        }
    ]
}
