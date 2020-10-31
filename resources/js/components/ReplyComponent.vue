<template>
    <div :id="'reply_' + id" class="card mt-3">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a
                        :href="'/profiles/' + reply.owner.name"
                        v-text="reply.owner.name"
                    ></a>
                    said
                    <span v-text="ago"></span>
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <wysiwyg v-model="body" name="body"></wysiwyg>
                        <!-- <textarea
                            class="form-control"
                            rows="5"
                            v-model="body"
                            required
                        ></textarea> -->
                    </div>

                    <button class="btn btn-sm btn-primary" type="submit">
                        Update
                    </button>
                    <button
                        class="btn btn-sm btn-link"
                        @click="cancel"
                        type="button"
                    >
                        Cancel
                    </button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>
        <div class="card-footer" v-if="authorize('owns', reply)">
            <div class="level">
                <div>
                    <button
                        class="btn btn-warning btn-sm mr-3"
                        @click="editing = true"
                    >
                        Edit
                    </button>
                    <button class="btn btn-danger btn-sm mr-3" @click="destroy">
                        Delete
                    </button>
                </div>
                <!-- <button
                    class="btn btn-primary btn-sm ml-auto"
                    @click="markBestReply"
                    v-if="authorize('owns', reply.thread)"
                >
                    Best Reply
                </button> -->
            </div>
        </div>
    </div>
</template>
<script>
import Favorite from "./FavoriteComponent.vue";
import moment from "moment";

export default {
    props: ["reply"],
    components: { Favorite },
    computed: {
        ago() {
            return moment(this.reply.created_at).fromNow();
        }
    },
    created() {
        // window.events.$on("best-reply-selected", id => {
        //     this.isBest = id == this.id;
        // });
    },
    data() {
        return {
            editing: false,
            id: this.reply.id,
            body: this.reply.body
            // isBest: this.reply.isBest
        };
    },
    methods: {
        update() {
            axios
                .patch("/replies/" + this.id, {
                    body: this.body
                })
                .then(() => {
                    this.editing = false;

                    flash("Reply has been updated.");
                })
                .catch(({ response: { data } }) => {
                    flash(data, "danger");
                });
        },
        destroy() {
            axios.delete("/replies/" + this.id);

            this.$emit("deleted", this.id);
        },
        cancel() {
            this.editing = false;
            this.body = this.reply.body;
        }
        // markBestReply() {
        //     axios
        //         .post(`/replies/${this.id}/best`)
        //         .then(() => {
        //             window.events.$emit("best-reply-selected", this.id);

        //             flash("You marked this reply as the best reply");
        //         })
        //         .catch(error => {
        //             if (error) {
        //                 flash("You cannot do this action", "danger");
        //             }
        //         });
        // }
    }
    // watch: {
    //     editing() {
    //         if (this.editing) {
    //             this.body = this.body.replace(/<a[^>]*>|<[^>]*>/gi, "");
    //         }
    //     }
    // }
};
</script>
