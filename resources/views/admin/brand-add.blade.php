@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Informations sur la marque</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{route('admin.brands')}}">
                            <div class="text-tiny">Marques</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Nouvelle marque</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{route('admin.brand.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Nom de la marque<span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nom de la marque" name="name" tabindex="0" value="{{old('name')}}" aria-required="true" required="">
                    </fieldset>
                    @error("name")
                        <span class="alert alert-danger text-center">
                            {{$message}}
                        </span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Slug de la marque<span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Slug de la marque" name="slug" tabindex="0" value="{{old('slug')}}" aria-required="true" required="">
                    </fieldset>
                    @error("slug")
                    <span class="alert alert-danger text-center">
                            {{$message}}
                        </span>
                    @enderror
                    <fieldset>
                        <div class="body-title">Télécharger images <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="{{asset('images/upload/upload-1.png')}}" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                    <span class="body-text">Déposez vos images ici ou sélectionnez <span
                                            class="tf-color">cliquez pour parcourir</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error("image")
                    <span class="alert alert-danger text-center">
                            {{$message}}
                        </span>
                    @enderror
                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            // Fonction pour afficher l'aperçu de l'image lors du chargement du fichier
            $("#myFile").on("change", function(e) {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            // Fonction pour générer le slug automatiquement en temps réel
            $("input[name='name']").on("input", function () {
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });
        });

        // Fonction pour convertir une chaîne de texte en slug
        function StringToSlug(text) {
            return text.toLowerCase()
                .replace(/[^\w\s-]+/g, "")  // Supprime les caractères spéciaux, sauf les espaces
                .replace(/\s+/g, "-")       // Remplace les espaces par des tirets
                .replace(/^-+|-+$/g, "");   // Supprime les tirets en début et fin de texte
        }
    </script>
@endpush

