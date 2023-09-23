<div class="row" id="genetic">
    <div class="col-md-8 col-md-offset-2">
        <form @submit.prevent="saveGenetic">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label for="name" class="col-md-4">Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" v-model="genetic.name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-md-4">Description</label>
                        <div class="col-md-8">
                            <textarea rows="2" class="form-control" v-model="genetic.description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-md-4"></label>
                        <div class="col-md-8">
                            <input type="submit" class="btn btn-success btn-block" value="Save">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <hr>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <div class="table-responsive">
            <datatable :columns="columns" :data="genetices" :filter-by="filter">
                <template scope="{ row }">
                    <tr>
                        <td>{{ row.sl }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.description }}</td>
                        <td>
                            <?php if($this->session->userdata('accountType') != 'u'){?>
                            <button type="button" class="button edit" @click="editgenetic(row)">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button type="button" class="button" @click="deletegenetic(row.id)">
                                <i class="fa fa-trash"></i>
                            </button>
                            <?php }?>
                        </td>
                    </tr>
                </template>
            </datatable>
            <datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vuejs-datatable.js"></script>
<script>
    const app = new Vue({
        el: '#genetic',
        data: {
            genetic: {
                id: null,
                name: '',
                description: ''
            },
            genetices: [],
            columns: [
                { label: 'Serial', field: 'sl', align: 'center', filterable: false },
                { label: 'Genetic Name', field: 'name', align: 'center' },
                { label: 'Description', field: 'description', align: 'center' },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        },
        created() {
            this.getGenetices();
        },
        methods: {
            getGenetices() {
                axios.post('/get_genetices')
                .then(res => {
                    this.genetices = res.data.map((item, sl) => {
                        item.sl = sl + 1;
                        return item;
                    })
                })
            },
            saveGenetic() {
                if(this.genetic.name == '') {
                    alert('Name is required');
                    return;
                }

                let url = '';
                if(this.genetic.id != null) {
                    url = '/update_genetic';
                } else {
                    url = '/add_genetic';
                    delete this.genetic.id;
                }

                axios.post(url, this.genetic)
                .then(res => {
                    alert(res.data.message);
                    if(res.data.success) {
                        this.resetForm();
                        this.getGenetices();
                    }
                })
                .catch(err => {
                    alert(err.response.data.message)
                })
            },
            editgenetic(genetic) {
                Object.keys(genetic).forEach(key => {
                    this.genetic[key] = genetic[key];
                })
            },
            deletegenetic(id) {
                if(confirm('Are you sure ?')) {
                    axios.post('/delete_genetic', {id: id})
                    .then(res => {
                        alert(res.data.message);
                        if(res.data.success) {
                            this.getGenetices();
                        }
                    })
                }
            },
            resetForm() {
                this.genetic.id = null;
                this.genetic.name = '';
                this.genetic.description = '';
            }
        }
    })
</script>