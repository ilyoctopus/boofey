<script>

    // import QueuesTable from "$lib/tables/QueuesTable.svelte";
    import AddQueueModal from "$lib/modals/add/AddQueueModal.svelte";
    import {InitFlatPickr} from "$lib/init/initFlatpickr.js"
    import Pagination from "$lib/components/Pagination.svelte";
    import SearchTable from "$lib/components/SearchTable.svelte";
	import ViewQueueModal from "$lib/modals/view/ViewQueueModal.svelte";
	import EditQueueModalSimple from "./EditQueueModalSimple.svelte";
	import DeleteQueueModal from "$lib/modals/delete/DeleteQueueModal.svelte";
    import AddQueueStudentModalSimple from "./AddQueueStudentModalSimple.svelte"

    import { goto } from '$app/navigation';



	import { onMount } from "svelte";
    import {initToolTip} from "$lib/init/initToolTip.js"
	import ActiveQueue from "$lib/components/cards/ActiveQueue.svelte";
	import QueuesTable from "$lib/tables/QueuesTable.svelte";
    import { setContext } from 'svelte';
    import { writable } from 'svelte/store';
    import { navigating } from '$app/stores';

    import { fade } from 'svelte/transition';
import CloseQueueModal from "./CloseQueueModal.svelte";
	import QueueCard from "./QueueCard.svelte";
	import ActiveQueueStudentCard from "./ActiveQueueStudentCard.svelte";
	import ViewAllQueuesModal from "./ViewAllQueuesModal.svelte";
    import ExitQueueStudent from "$lib/modals/confirmation/ExitQueueStudent.svelte";
    import { invalidate,beforeNavigate } from '$app/navigation';


    export let queuesList 
    export let activeQueue 
    export let canteen 
    export let queuesPagination
    export let activeQueueStudents

    let queuesPage
    let interval
    onMount(() => {
        initToolTip(queuesPage)
        // InitFlatPickr()
        interval = setInterval(() => {
            invalidate("queues:refresh")
        }, 10000)

    })
    setContext('queueStudentStore', {
        queueStudentStore: writable({})
    });


    let qrExit

    function openQrExit(){
        let toolTipInstance = bootstrap.Tooltip.getOrCreateInstance(qrExit)
        // goto(`/admin/queues/${queue.id}/queueStudents`)
        goto(`/admin/canteens/${canteen.id}/qr-exit`)
        
        toolTipInstance.hide()
    }


    let orderToolTip

    function openOrders(){
        let toolTipInstance = bootstrap.Tooltip.getOrCreateInstance(orderToolTip)
        // goto(`/admin/queues/${queue.id}/queueStudents`)
        goto(`/admin/canteens/${canteen.id}/orders`)
        
        toolTipInstance.hide()
    }

    beforeNavigate( () => {
        if(interval){
            clearInterval(interval)
        }
    })

    function resetInterval(){
        clearInterval(interval)
        interval = setInterval(() => {
            invalidate("queues:refresh")
        }, 10000)
    }
    
    
</script>
<div class="row"  in:fade={{duration: 200 }} bind:this={queuesPage}>
    <div class="col-lg-12">
        <div class="card mb-2">
            <div class="card-header align-items-center d-flex flex-wrap">

                <div class="d-flex gap-2 align-items-center col-sm-12 col-md-3">
                    <div class="flex-shrink-0">
                        <img src={canteen.school.logo.full_path} alt="" class="avatar-xs rounded-circle" />
                    </div>
                    <h4 class="card-title mb-0 flex-grow-1"><span class="text-primary">{canteen.school.name}</span> - <span class="text-primary">{canteen.name}</span> - Queue Managment</h4>
                </div> 

               
                <div class=" col-sm-12 col-md-9">
                    <div class="d-flex gap-2 justify-content-md-end justify-content-xs-start flex-wrap">

                            {#if activeQueue && JSON.parse(sessionStorage.getItem("permissions")).includes("queues.close.simplified")}
                           
                            <span data-bs-toggle="modal" class="col-auto col-xs-2"   data-bs-target="#closeQueueModal" >
                                <button type="button" class="btn  col-12 text-start btn-danger btn-label  waves-effect waves-light">
                                    <i class="ri-stop-circle-line label-icon align-middle fs-16 me-2"></i> Close queue
                                </button>
                            </span>

                            {:else if JSON.parse(sessionStorage.getItem("permissions")).includes("queues.store.simplified")}
                            <span data-bs-toggle="modal" class="col-auto col-xs-2"  data-bs-target="#addQueueModal">
                                <button type="button" class="btn  col-12 text-start btn-success btn-label  waves-effect waves-light">
                                    <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Open Queue
                                </button>
                            </span>
                            {/if}
                            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("queues.index.simplified")}
                                <span  data-bs-toggle="modal" class="col-auto col-xs-2"  data-bs-target="#ViewAllQueuesModal">
                                    <button type="button" class="btn  col-12 text-start btn-secondary btn-label  waves-effect waves-light">
                                        <i class="ri-team-line label-icon align-middle fs-16 me-2"></i> Queues
                                    </button>
                                </span>
                            {/if}
                            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("queues.exit.simplified")}
                                <span on:click={openQrExit} bind:this={qrExit} class="col-auto col-xs-2 ">
                                    <button type="button" class="btn  col-12 text-start btn-info btn-label  waves-effect waves-light">
                                        <i class="ri-qr-code-line label-icon align-middle fs-16 me-2"></i> Qr Exit
                                    </button>
                                </span>
                            {/if}
                            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("orders.index")}
                                <span on:click={openOrders} bind:this={orderToolTip} class="col-auto col-xs-2">
                                    <button type="button" class="btn  col-12 text-start btn-info btn-label  waves-effect waves-light">
                                        <i class="ri-survey-line label-icon align-middle fs-16 me-2"></i> POS
                                    </button>
                                </span>
                            {/if}
                        </div>

                    
                    
                    <AddQueueModal {canteen}/>

                    <ViewQueueModal />
                    <EditQueueModalSimple /> 
                    <DeleteQueueModal />
                    <CloseQueueModal queue={activeQueue}/>

                </div>
            </div><!-- end card header -->
        </div><!-- end card -->
        {#if $navigating?.from?.route?.id == $navigating?.to?.route?.id  && $navigating}
            <div class="text-center">
                <lord-icon src="https://cdn.lordicon.com/xjovhxra.json" trigger="loop" colors="primary:#E16F28,secondary:#73dce9" style="width:120px;height:120px"></lord-icon>
            </div>
        {:else}
        <div class="card-header align-items-center d-flex mb-3">
            <h4 class="card-title mb-0 flex-grow-1">Active Queue Students</h4>
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("queueStudents.store.simplified")}
            <button type="button" data-bs-toggle="modal" data-bs-target="#addQueueStudentModal" class="btn btn-primary waves-effect waves-light flex-shrink-0"><i class="ri-add-line align-bottom me-1"></i>Add Student</button>
            {/if}
        </div>
        {#if JSON.parse(sessionStorage.getItem("permissions")).includes("queues.index.simplified")}
            {#if activeQueue}
            <!-- <QueueCard queue={activeQueue} /> -->
            <div class="row g-3">

                {#each activeQueueStudents as queueStudent}
                     <ActiveQueueStudentCard {queueStudent}/>
                {:else}
                <div class="alert alert-info alert-border-left alert-dismissible fade show" role="alert">
                    <i class="ri-airplay-line me-3 align-middle"></i> There are no <strong>Students</strong> at the moment 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                {/each}
            
            </div>
            {:else}
                <div class="alert alert-info alert-border-left alert-dismissible fade show" role="alert">
                    <i class="ri-airplay-line me-3 align-middle"></i> There are no <strong>Active Queues</strong> at the moment 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                
            {/if}
            {/if}
        {/if}
<!--     
                <h4 class="card-title mb-0 flex-grow-1 mb-3">All queues</h4>
    
            {#if JSON.parse(sessionStorage.getItem("permissions")).includes("queues.index.simplified")}
        
                    <div class="row">
                        {#each queuesList as queue,_ (queue.id)}
                            <QueueCard {queue} />
                        {/each}
                        <Pagination {...queuesPagination} />
                    </div>
            {/if}
         -->
       
    
    </div>
                 <!--  
             <DeleteQueueModal /> -->
            <!--  
     end col -->
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

<AddQueueStudentModalSimple queue={activeQueue} {canteen}/>
<ExitQueueStudent route={"queues"} on:exit={resetInterval}/>
<ViewAllQueuesModal {queuesList} {activeQueue} {canteen} {queuesPagination}/>

    