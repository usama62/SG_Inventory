<template>
    <a-row>
        <a-col :span="24">
            <div v-if="!table.loading" class="table-responsive">
                <a-table
                    :columns="productOrderColumns"
                    :row-key="(record) => record.xid"
                    :data-source="table.data"
                    :pagination="table.pagination"
                    :loading="table.loading"
                    @change="handleTableChange"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'order_type'">
                            {{
                                getOrderTypeFromstring(record.order.order_type)
                            }}
                        </template>
                        <template v-if="column.dataIndex === 'order_date'">
                            {{ formatDateTime(record.order.order_date) }}
                        </template>
                        <template v-if="column.dataIndex === 'quantity'">
                            {{ record.quantity }}
                            {{
                                record.product &&
                                record.product.unit &&
                                record.product.unit.short_name
                                    ? record.product.unit.short_name
                                    : ""
                            }}
                        </template>
                        <template
                            v-if="column.dataIndex === 'single_unit_price'"
                        >
                            {{ formatAmountByCurrencyCode(record.single_unit_price, record.price_currency) }}
                        </template>
                        <template v-if="column.dataIndex === 'total_discount'">
                            {{ formatAmountByCurrencyCode(record.total_discount, record.price_currency) }}
                            ({{ record.discount_rate }}%)
                        </template>
                        <template v-if="column.dataIndex === 'total_tax'">
                            {{ formatAmountByCurrencyCode(record.total_tax, record.price_currency) }} ({{
                                record.tax_rate
                            }}%)
                        </template>
                        <template v-if="column.dataIndex === 'subtotal'">
                            {{ formatAmountByCurrencyCode(record.subtotal, record.price_currency) }}
                        </template>
                    </template>
                    <template #summary>
                        <a-table-summary-row>
                            <a-table-summary-cell :col-span="1">
                            </a-table-summary-cell>
                            <a-table-summary-cell
                                v-if="productType == 'variable'"
                                :col-span="1"
                            >
                            </a-table-summary-cell>

                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{ $t("common.total") }}
                                </a-typography-text>
                            </a-table-summary-cell>
                            <!-- <a-table-summary-cell :col-span="1">
                            </a-table-summary-cell> -->
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{
                                      (totals.totalQuantity)
                                    }}
                                </a-typography-text>
                            </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                            </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{
                                        formatAmountCurrency(totals.totalDiscount)
                                    }}
                                </a-typography-text>
                            </a-table-summary-cell>
                            <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{
                                        formatAmountCurrency(totals.totalTax)
                                    }}
                                </a-typography-text>
                            </a-table-summary-cell>
                              <a-table-summary-cell :col-span="1">
                                <a-typography-text strong>
                                    {{
                                        formatAmountCurrency(totals.totalAmount)
                                    }}
                                </a-typography-text>
                            </a-table-summary-cell>
                        </a-table-summary-row>
                    </template>
                </a-table>
            </div>
        </a-col>
    </a-row>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from "vue";
import datatable from "../../../../../common/composable/datatable";
import common from "../../../../../common/composable/common";
import fields from "./fields";

export default defineComponent({
    props: ["product"],
    setup(props) {
        const { productOrderColumns, orderItemsHashableColumns } = fields();
        const {
            formatDate,
            formatDateTime,
            formatAmountCurrency,
            formatAmountByCurrencyCode,
            getOrderTypeFromstring,
        } = common();
        const datatableVariables = datatable();

        onMounted(() => {
            datatableVariables.tableUrl.value = {
                url: "order-items?fields=id,xid,quantity,single_unit_price,unit_price,price_currency,total_discount,discount_rate,total_tax,tax_rate,subtotal,order_id,x_order_id,order{id,xid,order_type,order_date},unit_id,x_unit_id,unit{id,xid,short_name},product_id,x_product_id,product{id,xid,unit_id,x_unit_id},product:unit{id,xid,short_name}",
                filters: {
                    product_id: props.product.xid,
                },
            };
            datatableVariables.hashable.value = [...orderItemsHashableColumns];
            datatableVariables.table.sorter = {
                field: "orders.order_date",
                order: "asc",
            };

            datatableVariables.fetch({
                page: 1,
            });
        });

        const totals = computed(() => {
            let totalAmount = 0;
            let totalTax = 0;
            let totalDiscount = 0;
            let totalQuantity = 0;
            datatableVariables.table.data.forEach((tableRowData) => {
                totalAmount += tableRowData.subtotal;
                totalTax += tableRowData.total_tax;
                totalDiscount += tableRowData.total_discount;
                totalQuantity += tableRowData.quantity;
            });
            return {
                totalAmount,
                totalTax,
                totalDiscount,
                totalQuantity
            };
        });

        return {
            productOrderColumns,
            orderItemsHashableColumns,
            ...datatableVariables,
            totals,
            formatDate,
            formatDateTime,
            formatAmountCurrency,
            formatAmountByCurrencyCode,
            getOrderTypeFromstring,
        };
    },
});
</script>
