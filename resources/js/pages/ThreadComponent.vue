<script>
import Replies from "../components/RepliesComponent.vue";
import SubscribeButton from "../components/SubscribeButtonComponent.vue";
import ImageUpload from "../components/ImageUploadComponent.vue";

export default {
    props: ["thread"],
    components: { Replies, SubscribeButton, ImageUpload },
    data() {
        return {
            repliesCount: this.thread.replies_count,
            locked: this.thread.locked,
            editing: false,
            form: {
                title: this.thread.title,
                body: this.thread.body
            },
            image: {
                src: this.thread.image_path,
                file: null
            }
        };
    },
    methods: {
        toggleLock() {
            let uri = `/locked-threads/${this.thread.slug}`;
            axios[this.locked ? "delete" : "post"](uri);
            this.locked = !this.locked;
        },
        update() {
            let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;

            let data = new FormData();
            data.append("title", this.form.title);
            data.append("body", this.form.body);

            if (this.image.file !== null) data.append("image", this.image.file);

            axios
                .post(uri, data, {
                    processData: false,
                    contentType: "multipart/form-data"
                })
                .then(() => {
                    this.editing = false;

                    flash("Your thread has been updated.");
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        flash(
                            "We encountered a problem when processing your requests. Please try again later.",
                            "danger"
                        );
                    }
                });
        },
        cancel() {
            this.editing = false;

            this.form = {
                title: this.thread.title,
                body: this.thread.body
            };
        },
        updateFormPanel(item) {
            this.repliesCount++;
            this.locked = item.isThreadLocked;
        },

        onLoad(image) {
            this.image.src = image.src;
            this.image.file = image.file;
        }
    }
};
</script>
