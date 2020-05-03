import EmptyRouterView from "../../components/EmptyRouterView";
import TasksList from "../../views/tasks/TasksList";
import TasksAdd from "../../views/tasks/TasksAdd";

export default {
    path: '/tasks',
    name: 'tasks',
    component: EmptyRouterView,
    title: 'Zlecenia',
    children: [
        {
            path: 'list',
            name: 'tasksList',
            component: TasksList,
            title: 'Lista zleceń'
        },
        {
            path: 'add',
            name: 'tasksAdd',
            component: TasksAdd,
            title: 'Dodaj nowe zlecenie'
        }
    ]
}
