<script>
	// import ViewInvoiceModal from "$lib/modals/view/ViewInvoiceModal.svelte";
    import InvoiceItem from "$lib/tables/items/parent/InvoiceItem.svelte";
import {translation} from "$lib/translation.js"
    
    import { setContext } from 'svelte';
    import { writable } from 'svelte/store';
    import { navigating } from '$app/stores';
    
    export let invoicesList
    setContext('invoiceStore', {
	    invoiceStore: writable({})
    });

</script>


<div class="row pe-0">
    <div class="table-responsive">
        <table class="table align-middle table-nowrap mb-0 border-top">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 25px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkAll" value="option1">
                        </div>
                    </th>
                    <th scope="col">{translation.id[localStorage.getItem("language")]}</th>
                    <th scope="col">{translation.invoiceRef[localStorage.getItem("language")]}</th>
                    <th scope="col">{translation.subRef[localStorage.getItem("language")]}</th>
                    <th scope="col">{translation.genAt[localStorage.getItem("language")]}</th>
                    <th scope="col">{translation.package[localStorage.getItem("language")]}</th>
                    <th scope="col">{translation.student[localStorage.getItem("language")]}</th>
                    <th scope="col">{translation.actions[localStorage.getItem("language")]}</th>

                </tr>
             </thead>
            {#if $navigating == null || $navigating?.from?.route?.id != $navigating?.to?.route?.id}
            <tbody class="list">
                {#each invoicesList as invoice}
                    <InvoiceItem {invoice} />
                {/each}
            </tbody>
            {/if}
        </table>
        <!-- <ViewInvoiceModal /> -->
             

    </div>
</div>

{#if $navigating?.from?.route?.id == $navigating?.to?.route?.id  && $navigating}
    <div class="text-center">
        <lord-icon src="https://cdn.lordicon.com/xjovhxra.json" trigger="loop" colors="primary:#E16F28,secondary:#73dce9" style="width:120px;height:120px"></lord-icon>
    </div>
{/if}