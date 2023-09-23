<div id="ExpireList">
    <div style="display:none;" v-bind:style="{display: expList.length > 0 ? '' : 'none'}">
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-12">
                <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="reportContent">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Product Id</th>
                            <th>Product Name</th>
                            <th>Category Name</th>
                            <th>Invoice</th>
                            <th>Add Date</th>
                            <th>Expire Date</th>
                            <th>Remaining Day</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, sl) in expList">
                            <td>{{ sl + 1 }}</td>
                            <td>{{ product.Product_Code }}</td>
                            <td>{{ product.Product_Name }}</td>
                            <td>{{ product.ProductCategory_Name }}</td>
                            <td>{{ product.PurchaseMaster_InvoiceNo }}</td>
                            <td>{{ product.AddTime | formatDate }}</td>
                            <td>{{ product.expire_date }}</td>
                            <td>{{ product.remaining_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row" style="display:none;padding-top: 15px;" v-bind:style="{display: expList.length > 0 ? 'none' : ''}">
        <div class="col-md-12 text-center">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Product Id</th>
                        <th>Product Name</th>
                        <th>Category Name</th>
                        <th>Expire Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan='5'>No records found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script>
    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(String(value)).format('MM/DD/YYYY')
        }
    })
</script>
<script>
    new Vue({
        el: '#ExpireList',
        data() {
            return {
                expList: []
            }
        },
        created() {
            this.getExpireList();
        },
        methods: {
            getExpireList() {
                axios.get('/get_expire_list').then(res => {
                    this.expList = res.data;
                })
            },

            async print(){
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Expire list</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var mywindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
				mywindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				mywindow.document.body.innerHTML += reportContent;

				mywindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				mywindow.print();
				mywindow.close();
			}
        }
    })
</script>