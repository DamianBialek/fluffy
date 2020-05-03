import EmptyRouterView from "../../components/EmptyRouterView";
import TasksList from "../../views/tasks/TasksList";
import TasksAdd from "../../views/tasks/TasksAdd";

export default {
    path: '/tasks',
    name: 'tasks',
    component: EmptyRouterView,
    meta: {
        title: 'Zlecenia',
    },
    redirect: '/tasks/list',
    children: [
        {
            path: 'list',
            name: 'tasksList',
            component: TasksList,
            meta: {
                title: 'Lista zleceń'
            }
        },
        {
            path: 'add',
            name: 'tasksAdd',
            component: TasksAdd,
            meta: {
                title: 'Dodaj nowe zlecenie'
            }
        }
    ]
}
