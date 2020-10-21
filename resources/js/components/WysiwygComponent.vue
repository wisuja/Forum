<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value" />
        <trix-editor
            ref="trix"
            input="trix"
            :placeholder="placeholder"
            autofocus
        ></trix-editor>
    </div>
</template>

<script>
import Trix from "trix";

export default {
    props: ["name", "value", "placeholder", "shouldClear"],
    mounted() {
        this.$refs.trix.addEventListener("trix-change", e => {
            this.$emit("input", e.target.innerHTML);
        });
        this.$watch("shouldClear", () => {
            this.$refs.trix.value = "";
            this.$emit("cleared");
        });
    }
};
</script>

<style>
.trix-button--icon-heading-1,
.trix-button--icon-quote,
.trix-button--icon-code,
.trix-button--icon-decrease-nesting-level,
.trix-button--icon-increase-nesting-level,
.trix-button--icon-attach {
    display: none;
}

span[data-trix-button-group="file-tools"] {
    display: none;
}
</style>
