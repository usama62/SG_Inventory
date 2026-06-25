<template>
    <a-row :gutter="16" class="mt-10">
        <a-col :xs="24" :sm="24" :md="12" :lg="12">
            <div class="po-party-section">
                <div class="po-party-heading">{{ $t("purchase_order.supplier_section") }}</div>
                <a-form-item
                    :label="$t('purchase_order.supplier_company_name')"
                    name="supplier_company_name"
                    class="required"
                >
                    <a-input
                        v-model:value="formData.supplier_company_name"
                        :placeholder="$t('purchase_order.supplier_company_name')"
                        :disabled="disabled"
                    />
                </a-form-item>
                <a-form-item
                    :label="$t('purchase_order.supplier_address')"
                    name="supplier_address"
                    class="required"
                >
                    <a-textarea
                        v-model:value="formData.supplier_address"
                        :placeholder="$t('purchase_order.supplier_address')"
                        :auto-size="{ minRows: 2, maxRows: 4 }"
                        :disabled="disabled"
                    />
                </a-form-item>
                <a-form-item
                    :label="$t('purchase_order.supplier_phone')"
                    name="supplier_phone"
                    class="required"
                >
                    <a-input
                        v-model:value="formData.supplier_phone"
                        :placeholder="$t('purchase_order.supplier_phone')"
                        :disabled="disabled"
                    />
                </a-form-item>
            </div>
        </a-col>
        <a-col :xs="24" :sm="24" :md="12" :lg="12">
            <div class="po-party-section">
                <div class="po-party-heading">{{ $t("purchase_order.ship_to_section") }}</div>
                <a-form-item
                    :label="$t('purchase_order.ship_to_name')"
                    name="ship_to_name"
                    class="required"
                >
                    <a-input
                        v-model:value="formData.ship_to_name"
                        :placeholder="$t('purchase_order.ship_to_name')"
                        :disabled="disabled"
                    />
                </a-form-item>
                <a-form-item
                    :label="$t('purchase_order.ship_to_company_name')"
                    name="ship_to_company_name"
                    class="required"
                >
                    <a-input
                        v-model:value="formData.ship_to_company_name"
                        :placeholder="$t('purchase_order.ship_to_company_name')"
                        :disabled="disabled"
                    />
                </a-form-item>
                <a-form-item
                    :label="$t('purchase_order.ship_to_address')"
                    name="ship_to_address"
                    class="required"
                >
                    <a-textarea
                        v-model:value="formData.ship_to_address"
                        :placeholder="$t('purchase_order.ship_to_address')"
                        :auto-size="{ minRows: 2, maxRows: 4 }"
                        :disabled="disabled"
                    />
                </a-form-item>
                <a-form-item
                    :label="$t('purchase_order.ship_to_phone')"
                    name="ship_to_phone"
                    class="required"
                >
                    <a-input
                        v-model:value="formData.ship_to_phone"
                        :placeholder="$t('purchase_order.ship_to_phone')"
                        :disabled="disabled"
                    />
                </a-form-item>
            </div>
        </a-col>
    </a-row>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        formData: {
            type: Object,
            required: true,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },
});
</script>

<style scoped>
.po-party-section {
    border: 1px solid #2b3a7a;
    padding: 12px;
    height: 100%;
}

.po-party-heading {
    background: #2b3a7a;
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
    padding: 6px 10px;
    margin: -12px -12px 12px;
    font-size: 12px;
}
</style>
