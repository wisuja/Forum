<template>
    <div>
        <div v-for="(reply, index) in items" v-bind:key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <p v-if="$parent.locked" class="lead text-center text-secondary mt-3">
            This thread is locked. You may not add replies to this thread.
        </p>
        <reply-form class="mt-3" @created="add" v-else></reply-form>
    </div>
</template>

<script>
import Reply from "./ReplyComponent.vue";
import ReplyForm from "./ReplyFormComponent.vue";
import Collection from "../mixins/Collection";

export default {
    components: { Reply, ReplyForm },
    mixins: [Collection],
    data() {
        return {
            dataSet: false,
            items: false
        };
    },

    created() {
        this.fetch();
    },

    methods: {
        fetch(page) {
            axios.get(this.url(page)).then(this.refresh);
        },
        refresh({ data }) {
            this.dataSet = data;
            this.items = data.data;

            window.scrollTo(0, 0);
        },
        url(page) {
            if (!page) {
                let query = location.search.match(/page=(\d+)/);

                page = query ? query[1] : 1;
            }

            return `${location.pathname}/replies?page=${page}`;
        }
    }
};
</script>
