<script>
import { createEventDispatcher } from 'svelte';
import { fade } from 'svelte/transition';

const dispatch = createEventDispatcher();


export let index
export let operation




function destroy(){

    if(operation?.status == "success"){
    }else {

    }


    setTimeout(async () => {
        dispatch("destroy",{index})
    }, 5000); 



}

function playAudio(audio){
    audio.play()
}


</script>


<div class="col-xxl-3 col-xl-4  col-lg-5  col-sm-6   col-xs-12"  out:fade={{duration: 500 }}>
    <div class="card p-3 py-2"   role="button" >
        <div class="row g-0">
            <div class="card-body text-center" on:click={() => {console.log(successAudio)}}>


                {#if operation?.status == "loading"}
                    <div class="row align-items-center justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                {:else if operation?.status == "error"}
                    <i class="ri-error-warning-line text-danger fs-1 align-middle" use:destroy></i>
                    <audio src="/assets/audio/fail.mp3" use:playAudio></audio>

                    {#if !JSON.parse(sessionStorage.getItem("permissions")).includes("queues.exit.simplified")}
                        {#if operation?.errors?.json}
                        <p class="text-danger">{operation.errors.json[0]}</p>
                        {/if}
                        {#if operation?.errors?.student_id}
                        <p class="text-danger">{operation.errors.student_id[0]}</p>
                        {/if}
                        {#if operation?.errors?.queue_student_id}
                        <p class="text-danger">{operation.errors.queue_student_id[0]}</p>
                        {/if}
                        {#if operation?.errors?.queue_id}
                        <p class="text-danger">{operation.errors.queue_id[0]}</p>
                        {/if}
                    {/if}

                    
                    
                    
                {:else if operation?.status == "success"}
                    <audio src="/assets/audio/success.wav" use:playAudio></audio>
                    <i class="ri-check-double-line text-success fs-1 align-middle" use:destroy></i>
                {/if}
                    
            </div>
        </div>
    </div>
</div>