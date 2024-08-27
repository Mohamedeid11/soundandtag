@extends('web.layouts.master')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<link rel="stylesheet" href="{{asset('css/web/about.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/side-nav.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/login.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/card.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/employees.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/profileCard.css?v=1')}}" />
<link rel="stylesheet" href="{{asset('css/web/profiles.css?v=1')}}" />
@endsection
@section('content')
<section class="about_section padding_top">
    <div class="container">
        <div class="row">
            <div class="section_tittle col-12">
                <h2 style="color:var(--secondary-color); font-weight: 400">{{__("global.arrange_employees_hierarchy")}}</h2>
            </div>
            <div class="col-md-3 col-12">
                <nav class="nav flex-column side-nav">
                    @foreach($categories as $category)
                        <a class="btn btn-link text-md-left choose-category" data-id="{{$category->id}}">{{$category->name}}</a>
                    @endforeach
                </nav>
            </div>

            <div class="col">
                <div class="contact_section_content" hidden>
                    @if($user->employees()->count() > 0)
                    <div class="row">
                        <div class="col-lg-10 m-auto">
                            <div class="sidebar_part">
                                <div class="single_sidebar d-flex">
                                    <div class="position-relative flex-fill h-100">
                                        <input type="search" name="search" placeholder="{{__('global.search_by_name')}}">
                                    </div>
                                    <button onclick="searchRow()" class="secondary-btn btn ml-2 d-flex justify-content-center align-items-center" style="border-radius: 6px">{{__('global.search')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row members mt-4">
                        @foreach($employees as $profile)
                            @php
                                $profile->full_name = $loop->index+1 . '- ' . $profile->user->name;
                                $profile->account_type = $profile->user->account_type;
                                $profile->position = $profile->user->position;
                            @endphp
                            <div class="col-lg-2 col-4 drag-box" draggable="true" data-category="{{$profile->category? $profile->category->id :0}}">
                                @include('web.partials.profile-card')
                            </div>
                        @endforeach
                    </div>
                    <button class="btn primary-btn mt-3 mx-auto d-block" onclick="submitOrder()">{{__('global.reorder')}}</button>
                    @else
                    <p class="my-5 w-100 text-center">{{__('global.no_employees_added')}} ({{$user->employees()->count()}}/{{$user->items}}) </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
@include('web.partials.axiosinit')
<script src="{{asset('js/web/drag_drop.js')}}"></script>
<script>
    let dragSrcEl;

    function handleDragStart(e) {
        this.style.opacity = '0.4';
        dragSrcEl = this;
        if(e.dataTransfer) {
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', this.innerHTML);
        }
    }

    function handleDragEnd(e) {
        this.style.opacity = '1';

        items.forEach(function (item) {
            item.classList.remove('over');
        });
    }

    function handleDragOver(e) {
        e.preventDefault();
        return false;
    }

    function handleDragEnter(e) {
        this.classList.add('over');
    }

    function handleDragLeave(e) {
        this.classList.remove('over');
    }

    function handleDrop(e) {
        e.stopPropagation(); // stops the browser from redirecting.
        if (dragSrcEl !== this) {
            dragSrcEl.innerHTML = this.innerHTML;
            if(e.dataTransfer)
                this.innerHTML = e.dataTransfer.getData('text/html');
            dragSrcEl.style.opacity = '1';
        }

        return false;
    }

    let items = document.querySelectorAll('.drag-box');
    items.forEach(function(item) {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragover', handleDragOver);
        item.addEventListener('dragenter', handleDragEnter);
        item.addEventListener('dragleave', handleDragLeave);
        item.addEventListener('dragend', handleDragEnd);
        item.addEventListener('drop', handleDrop);
    });

    // Submit order employees
    const submitOrder = () => {
        const employees = document.querySelectorAll(".drag-box .single_team_member");
        const ids = {};
        const categoryId = employees[0].dataset.category;

        Array.from(employees).forEach((employee, i) => {
            ids[employee.dataset.id] = i + 1
        });

        axios.post("account/arrange-employees", {
                ids
            })
            .then(function(response) {
                swal({
                    title: "{{__('admin.success')}}",
                    text: "{{__('global.reorder_success')}}",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "primary-btn",
                    confirmButtonText: "{{__('global.done')}}",
                    closeOnConfirm: false
                }, function(confirm) {
                    if (confirm) {
                        window.location.reload();
                    }
                });
            }).catch(function(err) {
                console.log(err);
            });
    }

    // Search for employee name 
    const searchRow = () => {
        const employeeNames = Array.from(document.querySelectorAll(".drag-box:not([hidden]) .team_member_info h4 a")).map(elm => elm.innerHTML.trim());

        // Remove any active rows style
        document.querySelectorAll(".drag-box").forEach(tr => tr.style.background = "transparent")

        const searchValue = document.querySelector("input[name='search']").value;
        
        // If no search
        if (!searchValue) return;
        
        // First employee identical
        const firstRow = employeeNames.findIndex(name => name.includes(searchValue));

        if (firstRow !== -1) {
            const row = document.querySelectorAll(".drag-box:not([hidden])")[firstRow];
            row.style.background = "rgba(0, 0, 0, 0.5)";
            window.scrollTo({
                top: row.getBoundingClientRect().y - row.getBoundingClientRect().height,
                left: 0,
                behavior: 'smooth'
            })
        }
    }

    document.querySelector("input[name='search']").addEventListener("keypress", (e) => {
        if(e.key === "Enter") searchRow();
    })

    // Select a category
    const selectCategory = (elm) => {
        const categoryId = elm.dataset.id;
        let index = 1;

        document.querySelectorAll(".choose-category").forEach(btn => btn.classList.remove("active"))
        elm.classList.add("active");

        document.querySelector(".contact_section_content").removeAttribute("hidden");
        document.querySelectorAll(".drag-box").forEach(box => {
            if(box.dataset.category === categoryId) {
                box.removeAttribute("hidden");
                const name = box.querySelector(".team_member_info h4 a").textContent.split(" ").slice(1).join(" ");
                box.querySelector(".team_member_info h4 a").textContent = `${index}- ${name}`;
                index++;
            }
            else
                box.setAttribute("hidden", "true");
        })

        searchRow();
    }

    document.querySelectorAll(".choose-category").forEach((btn, i) => {
        if(!i) selectCategory(btn);

        btn.addEventListener("click", (e) => selectCategory(e.currentTarget));
    })
</script>

@endsection
