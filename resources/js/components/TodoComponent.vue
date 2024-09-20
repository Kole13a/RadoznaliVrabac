<template>
    <div class="container w-75 mt-4">
        <div class="row">
            <form @submit.prevent="editToggle ? updateMethod() : saveMethod()" class="col-12 col-lg-6 mb-3">
                <div class="rounded p-3">
                    <div class="input-group input-group-lg mb-2">
                        <input 
                            v-model="form.title" 
                            :class="{'is-invalid' : form.errors.has('title')}" 
                            type="text" 
                            class="form-control" 
                            @keydown="form.errors.clear('title')" 
                            placeholder="Vrapceva obaveza se zove..." 
                        />
                    </div>
                    <div class="input-group input-group-lg mb-2">
                        <textarea 
                            v-model="form.description" 
                            :class="{'is-invalid' : form.errors.has('description')}" 
                            class="form-control" 
                            @keydown="form.errors.clear('description')" 
                            placeholder="Vrapceva obaveza je da..." 
                            rows="8"
                        ></textarea>
                    </div>
                    <div>
                        <button class="btn btnClass btn-lg btn-block mt-2" type="submit">{{ editToggle ? 'Sačuvaj' : 'Dodaj' }}</button>
                    </div>
                    <span class="text-danger" v-if="form.errors.has('title')" v-text="form.errors.get('title')"></span><br>
                    <span class="text-danger" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></span>
                </div>
            </form>
            <div class="col-12 col-lg-6">
                <div class="border-white border-2 rounded p-3 scrollable-div">
                    <div v-for="todo in todos" :key="todo.id" class="todo-item d-flex flex-column flex-sm-row align-items-center bg-white border-bottom gap-1 p-2">
                        <span class="mr-3 d-flex align-items-center">
                            <icon name="completedOff" v-if="!todo.completed" v-on:click="completedMethod(todo)"></icon>
                            <icon name="completedOn" v-if="todo.completed" v-on:click="completedMethod(todo)"></icon>
                        </span>
                        <div class="flex-grow-1">
                            <h5 class="text-center mb-1">{{ todo.title }}</h5>
                            <div class="description-container">
                                <p class="mb-0">{{ todo.description }}</p>
                            </div>
                        </div>
                        <div class="ml-auto d-flex align-items-center justify-content-end">
                            <span class="mr-2">
                                <icon name="editOff" v-if="editToggle !== todo.id" v-on:click="editMethod(todo)"></icon>
                                <icon name="editOn" v-if="editToggle === todo.id"></icon>
                            </span>
                            <span>
                                <icon name="delete" v-on:click="deleteMethod(todo)"></icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Icon from "./Shared/Icon.vue";
    export default {
        data() {
            return {
                editToggle: false,
                todos: [],
                form: new Form({
                    title: "",
                    description: "",
                }),
                currentTodoId: null,
            };
        },
        components: {
            Icon,
        },
        methods: {
            deleteMethod(event) {
                let data = new FormData();
                data.append("_method", "DELETE");
                axios.post("/api/todo/" + event.id, data)
                    .then(() => {
                        this.getMethod();
                    })
                    .catch((error) => {
                        this.form.errors.record(error.response.data.errors);
                    });
            },
            editMethod(todo) {
                axios.get(`/api/todo/${todo.id}`)
                .then((response) => {
                    this.form.title = response.data.title;
                    this.form.description = response.data.description;
                    this.currentTodoId = response.data.id;
                    this.editToggle = true;
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            updateMethod() {
                let data = new FormData();
                data.append("title", this.form.title);
                data.append("description", this.form.description);
                data.append("_method", "PATCH");

                axios.post("/api/todo/" + this.currentTodoId, data)
                    .then(() => {
                        this.editToggle = false;
                        this.form.reset();
                        this.getMethod();
                    })
                    .catch((error) => {
                        this.form.errors.record(error.response.data.errors);
                    });
            },
            completedMethod(event) {
                event.completed = !event.completed;
                let data = new FormData();
                data.append("_method", "PATCH");
                data.append("completed", event.completed ? 1 : 0);
                axios.post("/api/todo/" + event.id, data).catch((error) => {
                    this.form.errors.record(error.response.data.errors);
                });
            },
            getMethod() {
                axios.get("/api/todo").then((response) => {
                    this.todos = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            },
            saveMethod() {
                let data = new FormData();
                data.append("title", this.form.title);
                data.append("description", this.form.description);
                axios.post("/api/todo", data)
                    .then(() => {
                        this.form.reset();
                        this.getMethod();
                    })
                    .catch((error) => {
                        this.form.errors.record(error.response.data.errors);
                    });
            },
        },
        mounted() {
            this.getMethod();
        },
    };
</script>
