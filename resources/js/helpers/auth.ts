import axios from "axios";

export function registerUser(credentials: any){
    return new Promise((res,rej)=>{
        axios.post('/api/auth/register', credentials)
            .then((response: { data: unknown; }) => {
                res(response.data);
            })
            .catch(() => {
                rej('An error occured.. try again later.')
            })
    })
}

export function login(credentials: any){
    return new Promise((res,rej)=>{
        axios.post('/api/auth/login', credentials)
            .then((response: { data: unknown; }) => {
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

export function isLoggedIn(): boolean {
    return !!getLoggedInUser();
}


export function getLoggedInUserToken(): string {
    return isLoggedIn() ? getLoggedInUser().token : null;
}
