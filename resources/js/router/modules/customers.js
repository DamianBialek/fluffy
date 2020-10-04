import CustomersList from "../../views/customers/CustomersList";
import CustomersAddCustomer from "../../views/customers/CustomersAddCustomer";
import CustomersEditCustomer from "../../views/customers/CustomersEditCustomer";
import EmptyRouterView from "../../components/EmptyRouterView";
import CustomersVehiclesList from "../../views/customersVehicles/CustomersVehiclesList";
import CustomersVehiclesAdd from "../../views/customersVehicles/CustomersVehiclesAdd";
import CustomersVehiclesEdit from "../../views/customersVehicles/CustomersVehiclesEdit";

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
                title: 'Lista klientów',
                inSidebar: true
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
        },
        {
            path: 'cars',
            component: EmptyRouterView,
            redirect: '/customers/cars/list',
            name: "cars",
            meta: {
                title: 'Pojazdy',
                inSidebar: true
            },
            children: [
                {
                    path: 'list',
                    component: CustomersVehiclesList,
                    name: 'customersCarsList',
                    meta: {
                        title: "Lista pojazdów"
                    }

                },
                {
                    path: 'add',
                    component: CustomersVehiclesAdd,
                    name: 'customersCarsAdd',
                    meta: {
                        title: "Dodawanie nowego pojazdu"
                    }

                },
                {
                    path: 'edit/:id',
                    name: 'customersCarsEdit',
                    component: CustomersVehiclesEdit,
                    meta: {
                        title: 'Edycja danych pojazdu'
                    }
                }
            ]
        }
    ]
}
