<script>
import { getContext } from "svelte"

    export let permission
    let {permissionStore} = getContext("permissionStore")
    
    function setPermission(){
        $permissionStore = JSON.parse(JSON.stringify(permission));
    }

</script>

<tr scope="row">
    <td>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option1" >
        </div>
    </td>
    <td>{permission.id}</td>
    <td>{permission.name}</td>
    <td>
        <div class="hstack gap-3 flex-wrap">
            
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("permissions.show")}
            <span data-bs-toggle="modal" data-bs-target="#viewPermissionModal" on:click={setPermission}><a href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-title="View" ><i class="ri-eye-fill"></i></a></span>
            {/if}
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("permissions.update")}
            <span data-bs-toggle="modal" data-bs-target="#editPermissionModal" on:click={setPermission}><a href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-title="Edit" ><i class="ri-edit-2-line"></i></a></span>
            {/if}
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("permissions.destroy")}
            <span data-bs-toggle="modal" data-bs-target="#deletePermissionModal" on:click={setPermission}><a href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-title="Delete"><i class="ri-delete-bin-line"></i></a></span>
            {/if}
        </div>
    </td>
</tr>