import { PathAuth } from "$lib/api/paths"
import { goto } from '$app/navigation';

export const ssr = false;
export async function load({fetch,cookies,url,depends}) {
    // const cookieStr = cookies.getAll()
    // .map(cookie => `${cookie.name}=${encodeURIComponent(cookie.value)}`).join('; ');

    let res = await fetch(PathAuth(),{
        headers:{
            Authorization: `${localStorage.getItem("SID")}`
        }
    })
    if(res.ok){
        let authResponse = await res.json()
        if(authResponse?.data?.user?.verified == false){
            goto("/verification")   
        }
        if(authResponse?.data?.user?.roles[0]?.name != "parent"){
            goto("/admin")
        }else{
            goto("/")
        }
    }




    return {tabTitle:"Sign In",arTabTitle:"تسجيل الدخول"}
};