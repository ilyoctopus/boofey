<script>
    import { getContext } from "svelte"
    import { goto } from '$app/navigation';

    export let school


    let {schoolStore} = getContext("schoolStore")
    
    function setSchool(){
        $schoolStore = JSON.parse(JSON.stringify(school));
    }

    let yearsToolTip
    let packagesToolTip
    let canteensToolTip
    let studentsToolTip

    function openAcademicYears(){
        let toolTipInstance = bootstrap.Tooltip.getOrCreateInstance(yearsToolTip)
        toolTipInstance.hide()
        goto(`schools/${school.id}/academicYears`)
    }

    function openPackages(){
        let toolTipInstance = bootstrap.Tooltip.getOrCreateInstance(packagesToolTip)
        toolTipInstance.hide()
        goto(`schools/${school.id}/packages`)
    }
    function openCanteens(){
        let toolTipInstance = bootstrap.Tooltip.getOrCreateInstance(canteensToolTip)
        toolTipInstance.hide()
        goto(`schools/${school.id}/canteens`)
    }
    function openSchoolStudents(){
        let toolTipInstance = bootstrap.Tooltip.getOrCreateInstance(studentsToolTip)
        toolTipInstance.hide()
        goto(`schools/${school.id}/students`)
    }




</script>

<tr scope="row">
    <td>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option1" >
        </div>
    </td>
    <td>{school.id}</td>
    <td>
        <div class="d-flex gap-2 align-items-center">
            <div class="flex-shrink-0">
                <img src={school.logo.full_path} alt="" class="avatar-xs rounded-circle" />
            </div>
            <div class="flex-grow-1">
                {school.name}
            </div>
        </div>
    </td>
    <td>
        {school.name_ar}
    </td>
    <td>
        {#if school.code == null}
            <span class="badge bg-dark-subtle text-body">not set</span>
        {:else}
            <span class="badge border border-primary text-primary">{school.code}</span>
        {/if}
    </td>
    <td>
        {#if school.qr_enabled}
        <span class="badge bg-success-subtle text-success">Enabled</span>
        {:else}
        <span class="badge bg-danger-subtle text-danger">Disabled</span>
        {/if}
    </td>

    <td>
        <div class="hstack gap-3 flex-wrap">
            <span on:click={openSchoolStudents}><a bind:this={studentsToolTip} href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-original-title="Students" ><i class="ri-user-2-line"></i></a></span>
            
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("canteens.indexBySchool")}
            <span on:click={openCanteens}><a bind:this={canteensToolTip} href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-original-title="Canteens" ><i class="ri-restaurant-2-fill"></i></a></span>
            {/if}
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("packages.indexBySchool")}
            <span on:click={openPackages}><a bind:this={packagesToolTip} href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-original-title="Packages" ><i class="ri-archive-line"></i></a></span>
            {/if}
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("academicYears.indexBySchool")}
            <span on:click={openAcademicYears}><a bind:this={yearsToolTip} href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-original-title="Academic Years" ><i class="ri-calendar-2-fill"></i></a></span>
            {/if}
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("schools.show")}
                <span data-bs-toggle="modal" data-bs-target="#viewSchoolModal" on:click={setSchool}><a href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-original-title="View" ><i class="ri-eye-fill"></i></a></span>
            {/if}
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("schools.update")}
                <span data-bs-toggle="modal" data-bs-target="#editSchoolModal" on:click={setSchool}><a href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-original-title="Edit" ><i class="ri-edit-2-line"></i></a></span>
            {/if}
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("schools.destroy")}
                <span data-bs-toggle="modal" data-bs-target="#deleteSchoolModal" on:click={setSchool}><a href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-original-title="Delete"><i class="ri-delete-bin-line"></i></a></span>
            {/if}
        
        </div>
    </td>
</tr>