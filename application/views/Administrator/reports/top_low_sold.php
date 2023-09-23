<style>
	.v-select{
		margin-bottom: 5px;
        float: right;
        min-width: 200px;
        margin-left: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
        height: 25px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
    #priceList label{
        font-size: 13px;
		margin-top: 3px;
    }
    #priceList select{
        border-radius: 3px;
        padding: 0px;
		font-size: 13px;
    }
    #priceList .form-group{
        margin-right: 10px;
    }
</style>
<div id="priceList">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 5px 0;">
        <div class="col-md-12">
            <form class="form-inline" @submit.prevent="getProducts">
                <div class="form-group">
                    <label>Sold Type</label>
                    <select class="form-control" v-model="searchType" style="width: 200px;">
                        <option value="top">Top Sold</option>
                        <option value="low">Low Sold</option>
                    </select>
                </div>

                <div class="form-group" style="margin-top: -5px;">
                    <input type="submit" value="Search">
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="display:none;margin-top: 15px;" v-bind:style="{display: products.length > 0 ? '' : 'none'}">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
        </div>
        <div class="col-md-12">
            <div class="table-responsive" id="reportContent">
                <table class="table table-bordered table-condensed" id="priceListTable">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Product Id</th>
                            <th>Product Name</th>
                            <th>Category Name</th>
                            <th>Sold Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, sl) in products">
                            <td>{{ sl + 1 }}</td>
                            <td>{{ product.Product_Code }}</td>
                            <td>{{ product.Product_Name }}</td>
                            <td>{{ product.ProductCategory_Name }}</td>
                            <td>{{ product.soldQty }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#priceList',
        data(){
            return {
                searchType: '',
                products: [],
                selectedProduct: null
            }
        },        
        methods: {           
            getProducts(){
                let soldType = '';
                if(this.searchType == 'top'){
                    soldType = 'DESC';
                } else {
                    soldType = 'ASC';
                }

                let data = {
                    soldType: soldType
                }
                axios.post('/get_top_sold', data).then(res => {
                    this.products = res.data;
                })
            },
            async print(){
				let reportContent = `
					<div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 style="text-align:center">Top & Low Sold Product List</h3>
                            </div>
                        </div>
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}, left=0, top=0`);
				reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

				reportWindow.document.body.innerHTML += reportContent;

				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
        }
    })
</script>