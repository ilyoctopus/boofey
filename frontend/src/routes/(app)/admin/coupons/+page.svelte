<script>
    import Pagination from "$lib/components/Pagination.svelte";
    import SearchTable from "$lib/components/SearchTable.svelte";
    
    import CouponsTable from "$lib/tables/CouponsTable.svelte";
    import AddCouponModal from "$lib/modals/add/AddCouponModal.svelte";
    import { onMount } from "svelte";
import { fade } from 'svelte/transition';
    import {initToolTip} from "$lib/init/initToolTip.js"
    export let data
    
    $: couponsList = data.couponsResponse.data  
    $: couponsPagination = data.couponsResponse.pagination
    let couponsPage
    onMount(() => {
        initToolTip(couponsPage)
    })
    
    </script>
    <div class="row"  in:fade={{duration: 200 }} bind:this={couponsPage}>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Coupons Managment</h4>
                    <div class="flex-shrink-0">
                    {#if JSON.parse(sessionStorage.getItem("permissions")).includes("coupons.store")}
                        <button type="button" data-bs-toggle="modal" data-bs-target="#addCouponModal" class="btn btn-primary waves-effect waves-light"><i class="ri-add-line align-bottom me-1"></i>Add Coupon</button>
                        <AddCouponModal />
                    {/if}
                    </div>
                </div><!-- end card header -->
                {#if JSON.parse(sessionStorage.getItem("permissions")).includes("coupons.index")}
                <div class="card-body">
    
                    <!-- <div class="live-preview"> -->
                        <div class="row">
                                <!-- Input with Icon -->
                            <SearchTable type={"Coupon"}/>
                            <CouponsTable {couponsList}/>
                            <Pagination {...couponsPagination} />
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
    
    