<template>
    <div class="app-container">
        <el-table
            v-loading="listLoading"
            :data="list"
            border
            fit
            highlight-current-row
            style="width: 100%"
        >
            <el-table-column align="center" label="ID" width="80">
                <template slot-scope="scope">
                    <span>{{ scope.row.id }}</span>
                </template>
            </el-table-column>

            <el-table-column width="120px" align="center" label="作者">
                <template slot-scope="scope">
                    <span>{{ scope.row.user }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="标题">
                <template slot-scope="scope">
                    <span>{{ scope.row.title }}</span>
                </template>
            </el-table-column>

            <el-table-column class-name="status-col" label="Status" width="110">
                <template slot-scope="{ row }">
                    <el-switch
                        @change="toggleStatus(row)"
                        v-model="row.status"
                        active-color="#13ce66"
                        inactive-color="#ff4949"
                    >
                    </el-switch>
                </template>
            </el-table-column>

            <el-table-column width="180px" align="center" label="创建时间">
                <template slot-scope="scope">
                    <span>{{
                        scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}')
                    }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="Actions" width="120">
                <template slot-scope="scope">
                    <router-link :to="'/article/edit/' + scope.row.id">
                        <el-button
                            type="primary"
                            size="small"
                            icon="el-icon-edit"
                        >
                            Edit
                        </el-button>
                    </router-link>
                </template>
            </el-table-column>
        </el-table>

        <pagination
            v-show="total > 0"
            :total="total"
            :page.sync="listQuery.page"
            :limit.sync="listQuery.limit"
            @pagination="getList"
        />
    </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import Resource from '@/api/resource';
import { updateArticle } from '@/api/article';
const articleResource = new Resource('articles');

export default {
    name: 'ArticleList',
    components: { Pagination },
    data() {
        return {
            list: null,
            total: 0,
            listLoading: true,
            listQuery: {
                page: 1,
                limit: 15,
            },
        };
    },
    created() {
        this.getList();
    },
    methods: {
        async getList() {
            this.listLoading = true;
            const { data, meta } = await articleResource.list(this.listQuery);
            this.list = data;
            this.total = meta.total;
            this.listLoading = false;
        },
        toggleStatus(article) {
            updateArticle(article.id, {
                status: article.status ? 1 : 0,
            })
                .then(() => {})
                .catch();
        },
    },
};
</script>

<style scoped>
.edit-input {
    padding-right: 100px;
}
.cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
}
</style>
