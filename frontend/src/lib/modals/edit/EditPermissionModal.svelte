<script>
    import { PathUpdatePermission } from "$lib/api/paths";
    import { toast } from "$lib/components/toast.js";
    import { invalidate } from '$app/navigation';
    import { getContext } from "svelte";
    import { redirector } from "$lib/api/auth";

    
    let {permissionStore} = getContext("permissionStore")

    let close
    let errors
let loading = false
    let form

    async function save(){
loading = true
        errors = {}
    
    
        let formData = new FormData(form)            
        let res = await fetch(PathUpdatePermission($permissionStore.id),{
            headers:{
                Authorization: `${localStorage.getItem("SID")}`
            },
            method:"POST",
            body:formData
        })
        redirector(res)

        res = await res.json()
        loading = false
        if(res.status == "success") {
            close.click()
            let text = `Edited #${$permissionStore.id} to ${$permissionStore.name}` 
            toast(text,"success")
            invalidate("permissions:refresh")
            reset()

        }else {
            errors = res.errors
        }

    
    
    }

    function reset(){
        errors = {}
    }
    
    </script>
    
    
    <div class="modal  fade" id="editPermissionModal" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true" on:hidden.bs.modal={reset}>
        <div class="modal-dialog modal-dialog-centered" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Edit Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                 <div class="modal-body" >
                {#if loading }
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/xjovhxra.json" trigger="loop" colors="primary:#E16F28,secondary:#73dce9" style="width:120px;height:120px"></lord-icon>
                </div>
                {/if}

                <form  on:submit|preventDefault={save} bind:this={form} class:d-none={loading}>
                        <div class="row g-3">
    
                                <div>
                                    <label for="firstName" class="form-label">Permission Name</label>
                                    <input type="text" class="form-control" name="name" id="firstName" placeholder="Enter Permission name" bind:value={$permissionStore.name}>
                                </div>
                                {#if errors?.name}
                                <strong class="text-danger ms-1 my-2">{errors.name[0]}</strong>
                                {/if}
    
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light fw-light" data-bs-dismiss="modal" bind:this={close}>Close</button>
                                    <input type="submit" class="btn btn-primary waves-effect waves-light" value="Save">
                                </div>
                        </div><!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>