<script>
    import Pagination from "$lib/components/Pagination.svelte";
    import SearchTable from "$lib/components/SearchTable.svelte";
    import AddPackageModal from "$lib/modals/add/AddPackageModal.svelte";
	import { onMount } from "svelte";
    import {initToolTip} from "$lib/init/initToolTip.js"
import { fade } from 'svelte/transition';
	import PackagesTable from "$lib/tables/PackagesTable.svelte";
    export let data
    $: packagesList = data.packagesResponse.data.packages
    $: school = data.packagesResponse.data.school  
    $: packagesPagination = data.packagesResponse.pagination

    let packagesPage
    onMount(() => {
        initToolTip(packagesPage)

    })
    
</script>
<div class="row"  in:fade={{duration: 200 }} bind:this={packagesPage}>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">

                <div class="d-flex gap-2 align-items-center flex-grow-1">
                <div class="flex-shrink-0">
                    <img src={school.logo.full_path} alt="" class="avatar-xs rounded-circle" />
                </div>
                <h4 class="card-title mb-0 flex-grow-1"><span class="text-primary">{school.name}'s</span> Packages Management</h4>
            </div> 

               
                <div class="flex-shrink-0">
                    {#if JSON.parse(sessionStorage.getItem("permissions")).includes("packages.storeBySchool")}
                    <button type="button" data-bs-toggle="modal" data-bs-target="#addPackageModal" class="btn btn-primary waves-effect waves-light"><i class="ri-add-line align-bottom me-1"></i>Add Package</button>
                    <AddPackageModal schoolId={school.id}/>
                    {/if}
                </div>
            </div><!-- end card header -->
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("packages.indexBySchool")}

            <div class="card-body">

                <!-- <div class="live-preview"> -->
                    <div class="row">
                            <!-- Input with Icon -->
                        <SearchTable type={"Package"}/>
                        <PackagesTable {packagesList}/>
                        <Pagination {...packagesPagination} />
                        <!--end col-->
                    </div>
                    <!--end row-->
                <!-- </div> -->
            </div><!-- end card-body -->
            {/if}
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>

<!-- src="https://boofey.akoops.com/uploads/schools/_5607de8d-e2d4-47e3-a808-d54851903b65.jpeg"
<div class="d-flex gap-2 align-items-center">
    <div class="flex-shrink-0">
        <img src={school.logo.full_path} alt="" class="avatar-xs rounded-circle" />
    </div>
    <div class="flex-grow-1">
        {school.name}
    </div>
</div> -->

    