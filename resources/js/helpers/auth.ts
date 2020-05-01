import axios from "axios";
import {decode} from "jsonwebtoken";
const jwt = require("jsonwebtoken");

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

export function checkIfTokenExpired(): boolean {
    const decoded = jwt.decode(getLoggedInUserToken());

    if(!(!!decoded) || !decoded.exp) {
        return true;
    }

    const exp = decoded.exp;

    return Date.now() >= exp * 1000;
}

export function refreshToken(){
    return new Promise((res,rej)=>{
        axios.post('/api/auth/refresh')
            .then((response: { data: unknown; }) => {
                res(response.data);
            })
            .catch(() => {
                rej('An error occured.. try again later.')
            })
    })
}
