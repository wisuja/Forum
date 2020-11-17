<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <wysiwyg
                    name="body"
                    id="body"
                    v-model="body"
                    placeholder="Have something to say?"
                    :shouldClear="completed"
                    @cleared="resetCompleted"
                ></wysiwyg>
                <!-- <textarea
                    name="body"
                    id="body"
                    class="form-control"
                    rows="5"
                    placeholder="Have something to say?"
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
                    name="post-image"
                    class="form-control-file mt-3"
                    @loaded="onLoad"
                ></image-upload>
            </div>
            <button type="submit" class="btn btn-primary" @click="addReply">
                Post
            </button>
        </div>
        <div v-else class="text-center">
            <p>
                You need to <a href="/login">sign in</a> to participate in the
                thread.
            </p>
        </div>
    </div>
</template>

<script>
import "at.js";
import "jquery.caret";
import ImageUpload from "./ImageUploadComponent.vue";

export default {
    components: { ImageUpload },
    data() {
        return {
            body: "",
            completed: false,
            image: {
                src: null,
                file: null
            }
        };
    },
    mounted() {
        $("#body").atwho({
            at: "@",
            delay: 750,
            callbacks: {
                remoteFilter: function(query, callback) {
                    $.getJSON("/api/users", { name: query }, function(
                        usernames
                    ) {
                        callback(usernames);
                    });
                }
            }
        });
    },
    methods: {
        addReply() {
            let data = new FormData();
            data.append("image", this.image.file);
            data.append("body", this.body);

            axios
                .post(location.pathname + "/replies", data, {
                    processData: false,
                    contentType: "multipart/form-data"
                })
                .then(({ data }) => {
                    this.body = "";
                    this.completed = true;
                    this.image.src = null;
                    this.image.file = null;

                    flash("Your reply has been posted.");

                    this.$emit("created", data);
                })
                .catch(({ response: { data } }) => {
                    this.body = "";
                    this.completed = true;

                    flash(data, "danger");
                });
        },
        resetCompleted() {
            this.completed = false;
        },
        onLoad(image) {
            this.image.src = image.src;
            this.image.file = image.file;
        }
    }
};
</script>
