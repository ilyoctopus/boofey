import { PathGetStudents,DefaultGetQueries } from "$lib/api/paths"

import { redirector } from "$lib/api/auth";

export const ssr = false;
export async function load({fetch,url,depends}) {
    depends('students:refresh');

    let res = await fetch(PathGetStudents(DefaultGetQueries(url),"parent"),{
        headers:{
            Authorization: `${localStorage.getItem("SID")}`
        }
    })
    redirector(res)

    let studentsResponse = await res.json() 
    console.log(studentsResponse)
    return {studentsResponse,tabTitle:"Students"}
};
