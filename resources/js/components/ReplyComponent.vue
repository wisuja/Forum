<template>
    <div :id="'reply_' + id" class="card mt-3">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <img
                        :src="reply.owner.avatar_path"
                        width="25"
                        height="25"
                    />
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
                    <div class="form-group">
                        <img
                            :src="image.src"
                            alt=""
                            width="200"
                            height="200"
                            v-if="image.src !== null"
                        />
                        <image-upload
                            name="image"
                            class="form-control-file mt-3"
                            @loaded="onLoad"
                        ></image-upload>
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
            <div v-else>
                <div v-html="body"></div>
                <img
                    :src="image.src"
                    alt=""
                    width="200"
                    height="200"
                    v-if="image.src !== null"
                />
            </div>
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
import ImageUpload from "./ImageUploadComponent.vue";

export default {
    props: ["reply"],
    components: { Favorite, ImageUpload },
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
            body: this.reply.body,
            image: {
                src: this.reply.image_path,
                file: null
            }
            // isBest: this.reply.isBest
        };
    },
    methods: {
        update() {
            let data = new FormData();
            data.append("body", this.body);

            if (this.image.file !== null) data.append("image", this.image.file);

            axios
                .post("/replies/" + this.id, data, {
                    processData: false,
                    contentType: "multipart/form-data"
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
        },

        onLoad(image) {
            this.image.src = image.src;
            this.image.file = image.file;
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
