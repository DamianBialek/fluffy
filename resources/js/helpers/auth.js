import axios from "axios";

export function registerUser(credentials){
    return new Promise((res,rej)=>{
        axios.post('/api/auth/register', credentials)
            .then(response => {
                res(response.data);
            })
            .catch(() => {
                rej('An error occured.. try again later.')
            })
    })
}

export function login(credentials){
    return new Promise((res,rej)=>{
        axios.post('/api/auth/login', credentials)
            .then(response => {
                res(response.data);
            })
            .catch(() => {
                rej('Wrong Email/Password combination.')
            })
    })
}

export function getLoggedInUser(){
    const userStr = localStorage.getItem('user');

    if(!userStr){
        return null
    }

    return JSON.parse(userStr);
}

export function isLoggedIn() {
    return !!getLoggedInUser();
}


export function getLoggedInUserToken() {
    return isLoggedIn() ? getLoggedInUser().token : null;
}
