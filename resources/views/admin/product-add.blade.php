@extends('layouts.admin')
@section('content')
    <!-- main-content-wrap -->
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Ajouter produit</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}"><div class="text-tiny">Dashboard</div></a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{route('admin.products')}}"><div class="text-tiny">Produits</div></a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Ajouter produit</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product.store')}}" >
                @csrf
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Nom du Produit <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Nom du Produit" name="name" tabindex="0" value="{{old('name')}}" aria-required="true">

                    </fieldset>
                    @error("name") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Slug du Produit <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Slug du Produit" name="slug" tabindex="0" value="{{old('slug')}}" aria-required="true">

                    </fieldset>
                    @error("slug") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Catégorie <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="category_id">
                                    <option value="">Choisir la catégorie</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error("category_id") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                        <fieldset class="brand">
                            <div class="body-title mb-10">Marque <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="brand_id">
                                    <option value="">Choisir la marque</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error("brand_id") <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>

                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Brève description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Brève description" tabindex="0" aria-required="true">{{old('short_description')}}</textarea>

                    </fieldset>
                    @error("short_description") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    <fieldset class="description">
                        <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true">{{old('description')}}</textarea>

                    </fieldset>
                    @error("description") <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                </div>

                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Télécharger images <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="{{asset('images/upload/upload-1.png')}}" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Déposez vos images ici ou sélectionnez <span class="tf-color">cliquez pour parcourir</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error("image") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    <fieldset>
                        <div class="body-title mb-10">Galerie d'images</div>
                        <div class="upload-image mb-16">
                            <div  id ="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="text-tiny">Déposez vos images ici ou sélectionnez <span class="tf-color">cliquez pour parcourir</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*" multiple>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error("images") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Prix normal <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Prix normal" name="regular_price" tabindex="0" value="{{old('regular_price')}}" aria-required="true">
                        </fieldset>
                        @error("regular_price") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                        <fieldset class="name">
                            <div class="body-title mb-10">Prix de vente <span class="tf-color-1"></span></div>
                            <input class="mb-10" type="text" placeholder="Prix de vente" name="sale_price" tabindex="0" value="{{old('sale_price')}}" aria-required="true">
                        </fieldset>
                        @error("sale_price") <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>


                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="SKU" name="SKU" tabindex="0" value="{{old('SKU')}}" aria-required="true">
                        </fieldset>
                        @error("SKU") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                        <fieldset class="name">
                            <div class="body-title mb-10">Quantité <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Quantité" name="quantity" tabindex="0" value="{{old('quantity')}}" aria-required="true">
                        </fieldset>
                        @error("quantity") <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Stock</div>
                            <div class="select mb-10">
                                <select class="" name="stock_status">
                                    <option value="instock">InStock</option>
                                    <option value="outofstock">Out of Stock</option>
                                </select>
                            </div>
                        </fieldset>
                        @error("stock_status") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                        <fieldset class="name">
                            <div class="body-title mb-10">À la une</div>
                            <div class="select mb-10">
                                <select class="" name="featured">
                                    <option value="0">Non</option>
                                    <option value="1">Oui</option>
                                </select>
                            </div>
                        </fieldset>
                        @error("featured") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    </div>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Ajouter un produit</button>
                    </div>
                </div>
            </form>
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>
    <!-- /main-content-wrap -->
@endsection

@push("scripts")
    <script>
        $(function(){
            // Gestion de l'aperçu de l'image
            $("#myFile").on("change", function(e){
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            // Gestion de l'aperçu de la galerie d'images
            $("#gFile").on("change", function(e){
                $(".gitems").remove();
                const gphotos = this.files;
                $.each(gphotos, function(key, val){
                    $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(val)}" alt=""></div>`);
                });
            });

            // Mise à jour du slug en temps réel
            $("input[name='name']").on("input", function(){
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });
        });

        // Fonction pour convertir en slug
        function StringToSlug(Text) {
            return Text.toLowerCase()
                .replace(/[^\w\s-]+/g, "")  // Retire les caractères non-alphanumériques
                .replace(/\s+/g, "-")       // Remplace les espaces par des tirets
                .replace(/^-+|-+$/g, "");   // Retire les tirets en début/fin
        }
    </script>
@endpush

