@extends('layouts.admin')
@section('content')
    <!-- main-content-wrap -->
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Modifier produit</h3>
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
                        <div class="text-tiny">Modifier le Produit</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product.update')}}" >
                <input type="hidden" name="id" value="{{$product->id}}" />
                @csrf
                @method("PUT")
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Nom du Produit <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Nom du Produit" name="name" tabindex="0" value="{{$product->name}}" aria-required="true">

                    </fieldset>
                    @error("name") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Slug du Produit <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Slug du Produit" name="slug" tabindex="0" value="{{$product->slug}}" aria-required="true">

                    </fieldset>
                    @error("slug") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Catégorie <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="category_id">
                                    <option value="">Choisir la catégorie</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}" {{$product->category_id == $category->id ? "selected":""}}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error("category_id") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                        <fieldset class="brand">
                            <div class="body-title mb-10">Marque <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="brand_id">
                                    <option value="">Choisir la marque </option>
                                    @foreach ($brands as $brand)
                                        <option value="{{$brand->id}}" {{$product->brand_id == $brand->id ? "selected":""}}>{{$brand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error("brand_id") <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>

                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Brève description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Brève description" tabindex="0" aria-required="true">{{$product->short_description}}</textarea>

                    </fieldset>
                    @error("short_description") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    <fieldset class="description">
                        <div class="body-title mb-10">Déscription <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10" name="description" placeholder="Déscription" tabindex="0" aria-required="true">{{$product->description}}</textarea>

                    </fieldset>
                    @error("description") <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                </div>

                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Télécharger images <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            @if($product->image)
                                <div class="item" id="imgpreview">
                                    <img src="{{asset('uploads/products')}}/{{$product->image}}" class="effect8" alt="">
                                </div>
                            @endif
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
                            @if($product->images)
                                @foreach(explode(",",$product->images) as $img)
                                    <div class="item gitems">
                                        <img src="{{asset('uploads/products')}}/{{trim($img)}}" class="effect8" alt="">
                                    </div>
                                @endforeach
                            @endif
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
                            <input class="mb-10" type="text" placeholder="Prix normal" name="regular_price" tabindex="0" value="{{$product->regular_price}}" aria-required="true">
                        </fieldset>
                        @error("regular_price") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                        <fieldset class="name">
                            <div class="body-title mb-10">Prix de vente <span class="tf-color-1"></span></div>
                            <input class="mb-10" type="text" placeholder="Prix de vente " name="sale_price" tabindex="0" value="{{$product->sale_price}}" aria-required="true">
                        </fieldset>
                        @error("sale_price") <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>


                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="SKU" name="SKU" tabindex="0" value="{{$product->SKU}}" aria-required="true">
                        </fieldset>
                        @error("SKU") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                        <fieldset class="name">
                            <div class="body-title mb-10">Quantité <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Quantité" name="quantity" tabindex="0" value="{{$product->quantity}}" aria-required="true">
                        </fieldset>
                        @error("quantity") <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    </div>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Stock</div>
                            <div class="select mb-10">
                                <select class="" name="stock_status">
                                    <option value="instock" {{$product->stock_status == "instock" ? "Selected":"" }}>InStock</option>
                                    <option value="outofstock" {{$product->stock_status == "outofstock" ? "Selected":"" }}>Out of Stock</option>
                                </select>
                            </div>
                        </fieldset>
                        @error("stock_status") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                        <fieldset class="name">
                            <div class="body-title mb-10">À la une</div>
                            <div class="select mb-10">
                                <select class="" name="featured">
                                    <option value="0" {{$product->featured == "0" ? "Selected":"" }}>Non</option>
                                    <option value="1" {{$product->featured == "1" ? "Selected":"" }}>Oui</option>
                                </select>
                            </div>
                        </fieldset>
                        @error("featured") <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    </div>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Modifier produit</button>
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

