<script>
    import { getContext } from "svelte"
    import { goto } from '$app/navigation';
    import {translation} from "$lib/translation.js"

    export let payment


    let {paymentStore} = getContext("paymentStore")
    
    function setPayment(){
        $paymentStore = JSON.parse(JSON.stringify(payment));
    }






</script>

<tr scope="row">
    <td>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option1" >
        </div>
    </td>
    <td>{payment.id}</td>
    <td><span class="badge border border-primary text-primary">{payment.ref}</span></td>
    <td>
        {#if payment?.subscription?.ref == null}
        <span class="badge bg-danger-subtle text-danger">{translation.notFound[localStorage.getItem("language")]}</span> 
        {:else}
        {payment.subscription.ref}
        {/if}
    </td>
    <td>             
        <img src={payment?.subscription?.student?.image?.full_path} class="avatar-xxs rounded-circle me-1" alt="">
        <a href="" class="text-reset">{payment?.subscription?.student?.fullname}</a>
    </td>
    <td>
        {#if payment.status == 14 || payment.status == null}
            <span class="badge bg-success-subtle text-success" id="invoice-status">{translation.success[localStorage.getItem("language")]}</span>
        {:else}
        <span class="badge bg-danger-subtle text-danger" id="invoice-status">{translation.failed[localStorage.getItem("language")]}</span>
        {/if}
    </td>
    <td>{payment.amount} {translation.sar[localStorage.getItem("language")]}</td>
    <td>{payment.paid_at}</td>

    <td>
        <div class="hstack gap-3 flex-wrap">
            
            <span data-bs-toggle="modal" data-bs-target="#viewPaymentModal" on:click={setPayment}><a href="javascript:void(0);" class="fs-15" data-bs-toggle="tooltip" data-bs-original-title="View" ><i class="ri-eye-fill"></i></a></span>
 
        </div>
    </td>
</tr>