<script>
    import { getContext } from "svelte";
    import { PathUpdateUser } from "$lib/api/paths";
    import { toast } from "$lib/components/toast.js";
    import { invalidate } from '$app/navigation';
    import { page } from '$app/stores';
    import { redirector } from "$lib/api/auth";
    import Password from "$lib/components/Password.svelte"
    let {userStore} = getContext("userStore")
    
    let close
    let form 
    let firstName = ""
    let lastName = ""
    let password = ""
    let roles = []
    let role
    let editPassword = false;
    let errors
let loading = false
    let verified
    async function save(){
loading = true
        errors = {}
        let formData = new FormData(form)
        // if(password == "" || editPassword == false){
        //     formData.delete("password")
        // }
        formData.set("edit_password",editPassword)
        formData.set("verified",verified)


        let res = await fetch(PathUpdateUser($userStore.id),{
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
            let text = `Edited ${$userStore.username} ` 
            toast(text,"success")
            invalidate("users:refresh")
            reset()
        }else {
            errors = res.errors
        }


        
    }

    userStore.subscribe(() => {
        firstName = $userStore?.profile?.firstname
        lastName = $userStore?.profile?.lastname
        roles = $page.data.usersResponse.data.roles
        password = ""
        verified = $userStore?.verified
        if(!$userStore?.roles?.length) return;
        role = $userStore?.roles[0]
    }) 

    function reset(){
        editPassword = false;
        form.reset()
        errors = {}

    }

    </script>
    
    
    <div class="modal  fade" id="editUserModal" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true" on:hidden.bs.modal={reset}>
        <div class="modal-dialog modal-dialog-centered" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Edit User</h5>
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
    
                                <div class="col-lg-12">
                                    <label for="username" class="form-label" >Username</label>
                                    <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username name" bind:value={$userStore.username} >
                                    {#if errors?.username}
                                    <strong class="text-danger ms-1 my-2">{errors.username[0]}</strong>
                                    {/if}
                                </div>
                                <div class="col-xxl-6">
                                    <div>
                                        <label for="firstName" class="form-label">First Name</label>
                                        <input type="text" name="firstname" class="form-control" id="firstName" placeholder="Enter Firstname" bind:value={firstName}>
                                        {#if errors?.firstname}
                                        <strong class="text-danger ms-1 my-2">{errors.firstname[0]}</strong>
                                        {/if}
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-xxl-6">
                                    <div>
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input type="text" name="lastname" class="form-control" id="lastName" placeholder="Enter Lastname" bind:value={lastName}>
                                        {#if errors?.lastname}
                                        <strong class="text-danger ms-1 my-2">{errors.lastname[0]}</strong>
                                        {/if}
                                    </div>
                                </div>
                                <div class="col-xxl-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter your Email" bind:value={$userStore.email}>
                                    {#if errors?.email}
                                    <strong class="text-danger ms-1 my-2">{errors.email[0]}</strong>
                                    {/if}
                                </div>
                                <div class="col-xxl-6">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" name="phone" class="form-control phone-input" id="phoneedit" placeholder="Enter your Phone no." bind:value={$userStore.phone}>
                                    {#if errors?.phone}
                                    <strong class="text-danger ms-1 my-2">{errors.phone[0]}</strong>
                                    {/if}
                                </div>

                                <div class="row ps-3 g-3">
                                    <!-- Switches Color -->
                                    <div class="form-check form-switch col" >
                                        <input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck1" bind:checked={editPassword}>
                                        <label class="form-check-label" for="SwitchCheck1">Edit Password </label>
                                    </div><!-- Switches Color -->

                                </div>
                                
                                {#if editPassword}
                                <div class="col-xxl-6">
                                    <label for="password" class="form-label">Password</label>
                                    <Password name={"password"} placeholder={"Enter Password"} bind:value={password}/>
                                    {#if errors?.password}
                                    <strong class="text-danger ms-1 my-2">{errors.password[0]}</strong>
                                    {/if}
                                </div>
                                {/if}
                                {#if editPassword}
                                <div class="col-xxl-6">
                                    <label for="passwordconfirm" class="form-label">Confirm Password</label>
                                    <Password name={"password_confirmation"} placeholder={"Confirm Password"}/>
                                    {#if errors?.password_confirmation}
                                    <strong class="text-danger ms-1 my-2">{errors.password_confirmation[0]}</strong>
                                    {/if}
                                </div>
                                {/if}

                                <div class="row ps-3 g-3">
                                    <!-- Switches Color -->
                                    <div class="form-check form-switch col" >
                                        <input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck1" bind:checked={verified}>
                                        <label class="form-check-label" for="SwitchCheck1">Verified</label>
                                    </div><!-- Switches Color -->

                                </div>

                                <div class="col-lg-12">
                                    <label for="role" class="form-label">Role</label>
                                    {#if role}
                                    <select class="form-select" name="role_id" id="role" aria-label="Default select example" bind:value={role.id}>
                                        <!-- <option disabled selected value> -- select an role -- </option> -->
                                        {#each roles as role (role.id)}
                                            <option value={role.id}>{role.name}</option>
                                        {/each}
                                    </select>
                                    {#if errors?.role_id}
                                    <strong class="text-danger ms-1 my-2">{errors.role_id[0]}</strong>
                                    {/if}
                                    {/if}
                                </div>    
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