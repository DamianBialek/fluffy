<template>
    <div>
        <form @submit.prevent="$emit('submit')">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="order-data-tab" data-toggle="tab" href="#order-data" role="tab" aria-controls="order-data" aria-selected="true">Dane</a>
                </li>
                <li class="nav-item" v-if="order.id">
                    <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="true">Dokumenty</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="positions-tab" data-toggle="tab" href="#positions" role="tab" aria-controls="profile" aria-selected="false">Pozycje zlecenia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="parts-tab" data-toggle="tab" href="#parts" role="tab" aria-controls="profile" aria-selected="false">Części</a>
                </li>
            </ul>
            <div class="tab-content m-3" id="myTabContent">
                <div class="tab-pane fade show active" id="order-data" role="tabpanel" aria-labelledby="order-data-tab">
                    <div class="form-group row" v-if="order.number">
                        <label for="number" class="col-sm-2 col-form-label">Numer</label>
                        <div class="col-sm-10">
                            <input disabled v-model="order.number" type="text" class="form-control" id="number">
                        </div>
                    </div>
                    <div class="form-group row" v-if="order.number">
                        <label for="stateName" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select v-model="order.state" class="form-control" id="stateName">
                                 <option v-for="orderState in availableOrderStates" :key="orderState.id" v-bind:value="orderState.id">{{orderState.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Osoba odpowiedzialna</label>
                        <div class="col-sm-10">
                            <div @click="openResponsiblePeopleModal" class="form-control">{{order.responsible_person ? `${order.responsible_person.name} ${order.responsible_person.surname}` : ''}}</div>
                        </div>
                    </div>
                    <DateHoursInput label="Data rozpoczęcia" v-model="order.start_date" />
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pojazd</label>
                        <div class="col-sm-10">
                            <div @click="openCustomerVehiclesModal" class="form-control">{{order.vehicle && order.vehicle.mark ? `${order.vehicle.mark} ${order.vehicle.model} (${order.vehicle.registration_number})` : ''}}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Klient</label>
                        <div class="col-sm-10">
                            <div @click="openCustomerDataModal" class="form-control">{{order.customer && order.customer.name ? `${order.customer.name} ${order.customer.surname}` : ''}}</div>
                        </div>
                    </div>
                    <DateHoursInput label="Data i godzina przyjęcia pojazdu" v-model="order.date_receipt_vehicle" />
                    <DateHoursInput label="Data i godzina odbioru pojazdu" v-model="order.date_delivery_vehicle" />
                    <div class="form-group row">
                        <label for="vehicle_mileage" class="col-sm-2 col-form-label">Przebieg pojazdu</label>
                        <div class="col-sm-10">
                            <input v-model="order.vehicle_mileage" type="text" class="form-control" id="vehicle_mileage">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nazwa</label>
                        <div class="col-sm-10">
                            <input v-model="order.name" type="text" class="form-control" id="name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="note" class="col-sm-2 col-form-label">Zakres prac zleconych przez klienta</label>
                        <div class="col-sm-10">
                            <textarea name="note" id="note" class="form-control" rows="5" v-model="order.note"></textarea>
                        </div>
                    </div>
                </div>
                <div v-if="order.id" class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                    <div class="form-group row">
                        <label for="number" class="col-sm-2 col-form-label">Protokół przyjęcia pojazdu</label>
                        <div class="col-sm-10">
                            <a target="_blank" :href="`/orders/${order.id}/acceptance-report`" class="btn btn-outline-secondary ml-2"><i class="fas fa-eye"></i> Zobacz</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="number" class="col-sm-2 col-form-label">Faktura VAT</label>
                        <div class="col-sm-10">
                            <b v-if="order.invoice_number">{{order.invoice_number}}</b>
                            <button v-if="!order.invoice_number" type="button" class="btn btn-outline-secondary" @click="$emit('generateInvoice')">Wygeneruj</button>
                            <a v-if="order.invoice_number" target="_blank" :href="`/orders/${order.id}/invoice`" class="btn btn-outline-secondary ml-2"><i class="fas fa-eye"></i> Zobacz</a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="positions" role="tabpanel" aria-labelledby="positions-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Lp</th>
                                <th>Nazwa</th>
                                <th class="text-center">Cena</th>
                                <th class="text-center">Ilość</th>
                                <th class="text-center">Wartość</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!orderPositionsServices.length"><td colspan="5" class="text-center"><strong>Brak usług</strong></td></tr>
                            <tr v-for="(position, index) in orderPositionsServices" :key="position.id">
                                <td @click="editPosition(position, position.origIndexInArray)" class="align-middle">{{index+1}}</td>
                                <td @click="editPosition(position, position.origIndexInArray)" class="align-middle">{{position.name}}</td>
                                <td @click="editPosition(position, position.origIndexInArray)" class="text-center align-middle">{{moneyFormat(position.price)}}</td>
                                <td @click="editPosition(position, position.origIndexInArray)" class="text-center align-middle">{{position.quantity}}</td>
                                <td @click="editPosition(position, position.origIndexInArray)" class="text-center align-middle">{{moneyFormat(positionTotalSum(position))}}</td>
                                <td class="text-center align-middle">
                                    <button v-tooltip="'Kopiuj'" type="button" class="btn btn-outline-secondary m-1" @click="copyPosition(position)"><i class="fas fa-clone"></i></button>
                                    <button v-tooltip="'Usuń'" type="button" class="btn btn-outline-danger m-1" @click="removePosition(position)"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            <tr class="lead text-dark" v-if="orderPositionsServices.length">
                                <td colspan="4" class="text-right"><strong>Razem:</strong></td>
                                <td class="font-weight-bold text-center">{{moneyFormat(orderPositionsServicesTotalSum)}}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button @click="createNewPosition('service')" type="button" class="btn btn-outline-secondary"><i class="fa fa-plus text-success mr-2" />Dodaj nową pozycję</button>
                </div>
                <div class="tab-pane fade" id="parts" role="tabpanel" aria-labelledby="parts-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Lp</th>
                                <th>Nazwa</th>
                                <th class="text-center">Cena</th>
                                <th class="text-center">Ilość</th>
                                <th class="text-center">Wartość</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="!orderPositionsParts.length"><td colspan="5" class="text-center"><strong>Brak części</strong></td></tr>
                            <tr v-for="(position, index) in orderPositionsParts" :key="position.id">
                                <td @click="editPosition(position, position.origIndexInArray)" class="align-middle">{{index+1}}</td>
                                <td @click="editPosition(position, position.origIndexInArray)" class="align-middle">{{position.name}}</td>
                                <td @click="editPosition(position, position.origIndexInArray)" class="text-center align-middle">{{moneyFormat(position.price)}}</td>
                                <td @click="editPosition(position, position.origIndexInArray)" class="text-center align-middle">{{position.quantity}}</td>
                                <td @click="editPosition(position, position.origIndexInArray)" class="text-center align-middle">{{moneyFormat(positionTotalSum(position))}}</td>
                                <td class="text-center align-middle">
                                    <button v-tooltip="'Szukaj w serwisie Allegro'" @click="searchOrderPosOnAllegro(position)" type="button" class="btn btn-outline-secondary">Allegro</button>
                                    <button v-tooltip="'Kopiuj'" type="button" class="btn btn-outline-secondary m-1" @click="copyPosition(position)"><i class="fas fa-clone"></i></button>
                                    <button v-tooltip="'Usuń'" type="button" class="btn btn-outline-danger m-1" @click="removePosition(position)"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            <tr class="lead text-dark" v-if="orderPositionsParts.length">
                                <td colspan="4" class="text-right"><strong>Razem:</strong></td>
                                <td class="font-weight-bold text-center">{{moneyFormat(orderPositionsPartsTotalSum)}}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button @click="createNewPosition('part')" type="button" class="btn btn-outline-secondary"><i class="fa fa-plus text-success mr-2" />Dodaj nową część</button>
                </div>
            </div>
            <div v-if="order.id" class="my-5 mx-3">
                <hr />
                <div class="form-group row">
                    <label for="created_at" class="col-sm-2 col-form-label">Data utworzenia</label>
                    <div class="col-sm-10">
                        <input disabled v-model="order.created_at" type="text" class="form-control" id="created_at">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="updated_at" class="col-sm-2 col-form-label">Data modyfikacji</label>
                    <div class="col-sm-10">
                        <input disabled v-model="order.updated_at" type="text" class="form-control" id="updated_at">
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button class="btn btn-primary">Zapisz</button>
            </div>
        </form>
        <div id="editedPositionModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{editedPositionModalMode === 'create' ? 'Nowa pozycja' : 'Edycja pozycji'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Nazwa</th>
                                    <th>Cena</th>
                                    <th>Ilość</th>
                                    <th class="text-center">Wartość</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input aria-label="Nazwa pozycji" :class="['form-control', {'is-invalid': fieldHasError('name')}]" v-model="editedPosition.name" />
                                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('name')"></div>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" aria-label="Cena pozycji" :class="['form-control', {'is-invalid': fieldHasError('price')}]" v-model="editedPosition.price" />
                                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('price')"></div>
                                    </td>
                                    <td>
                                        <input aria-label="Ilość" :class="['form-control', {'is-invalid': fieldHasError('quantity')}]" v-model="editedPosition.quantity" />
                                        <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('quantity')"></div>
                                    </td>
                                    <td class="text-center">{{moneyFormat(positionTotalSum(editedPosition))}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-2">
                            <button @click="submitEditPositionModal" class="btn btn-primary">Zapisz</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="responsiblePeopleModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Wybierz osobę odpowiedzialną za zlecenie</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-8">
                                <div class="input-group">
                                    <input @keyup="searchResponsiblePerson" v-model="responsiblePersonSearchQuery" aria-label="Szukaj osoby" type="text" class="form-control" />
                                    <div class="input-group-append">
                                        <button @click="searchResponsiblePerson" type="button" class="btn btn-outline-secondary"><i class="fas fa-search mr-2"></i>Szukaj</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <Loading v-if="responsiblePersonLoading" />
                        <div v-show="!responsiblePersonLoading" class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th>Imię</th>
                                    <th>Nazwisko</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="person in responsiblePeople" :key="person.id">
                                    <th scope="row">{{person.id}}</th>
                                    <td>{{person.name}}</td>
                                    <td>{{person.surname}}</td>
                                    <td class="text-center">
                                        <button @click="selectResponsiblePerson(person)" type="button" class="btn btn-outline-secondary m-1"><i class="fa fa-plus text-success mr-2" />Wybierz</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 pb-3 d-flex justify-content-center">
                            <Pagination :pagination="responsiblePeoplePaginationData" @paginate="loadResponsiblePeople()" :offset="2"></Pagination>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="customerVehiclesModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Wybierz pojazd</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-8">
                                <div class="input-group">
                                    <input @keyup="searchCustomerVehiclesByQuery" v-model="customerVehiclesSearchQuery" aria-label="Szukaj pojazdu" type="text" class="form-control" />
                                    <div class="input-group-append">
                                        <button @click="searchCustomerVehiclesByQuery" type="button" class="btn btn-outline-secondary"><i class="fas fa-search mr-2"></i>Szukaj</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <Loading v-if="customersVehiclesLoading" />
                        <div v-show="!customersVehiclesLoading" class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th>Numer rejestarcyjny</th>
                                    <th>Marka i model (rok produkcji)</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="vehicle in customerVehicles" :key="vehicle.id">
                                    <th scope="row">{{vehicle.id}}</th>
                                    <td>{{vehicle.registration_number}}</td>
                                    <td>{{`${vehicle.mark} ${vehicle.model} (${vehicle.production_year})`}}</td>
                                    <td class="text-center">
                                        <button @click="selectCustomerVehicle(vehicle)" type="button" class="btn btn-outline-secondary m-1"><i class="fa fa-plus text-success mr-2" />Wybierz</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 pb-3 d-flex justify-content-center">
                            <Pagination :pagination="customersVehiclesPaginationData" @paginate="loadCustomersVehicles()" :offset="2"></Pagination>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="customerDataModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Dane klienta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="customerCompany" class="col-sm-2 col-form-label">Firma</label>
                            <div class="col-sm-10">
                                <input v-model="order.customer.company" type="text" :class="['form-control', {'is-invalid': fieldHasError('company')}]" id="customerCompany">
                                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('company')"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="customerName" class="col-sm-2 col-form-label">Imię</label>
                            <div class="col-sm-10">
                                <input v-model="order.customer.name" type="text" :class="['form-control', {'is-invalid': fieldHasError('name')}]" id="customerName">
                                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('name')"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="surname" class="col-sm-2 col-form-label">Nazwisko</label>
                            <div class="col-sm-10">
                                <input v-model="order.customer.surname" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('surname')}]" id="surname">
                                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('surname')"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-2 col-form-label">Adres</label>
                            <div class="col-sm-10">
                                <input v-model="order.customer.address" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('address')}]" id="address">
                                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('address')"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="postcode" class="col-sm-2 col-form-label">Kod pocztowy</label>
                            <div class="col-sm-10">
                                <input v-model="order.customer.postcode" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('postcode')}]" id="postcode">
                                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('postcode')"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="city" class="col-sm-2 col-form-label">Miasto</label>
                            <div class="col-sm-10">
                                <input v-model="order.customer.city" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('city')}]" id="city">
                                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('city')"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-2 col-form-label">Telefon</label>
                            <div class="col-sm-10">
                                <input v-model="order.customer.phone" type="text" class="form-control" :class="['form-control', {'is-invalid': fieldHasError('phone')}]" id="phone">
                                <div class="invalid-feedback text-center" v-html="fieldInvalidFeedback('phone')"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zapisz</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="allegroOrderPosResults" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Allegro - wyniki wyszukiwania</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" @scroll="onAllegroResultsModalScroll">
                        <Loading v-if="allegro.loading" />
                        <div v-if="!allegro.loading">
                            <h5 class="text-center mb-3">Wyszukiwana fraza: <b>{{allegro.searchingPhrase}}</b></h5>
                            <table class="table">
                                <tr v-for="result in allegro.results" :key="result.id">
                                    <td>
                                        <img style="max-width: 200px;" class="img-fluid" v-if="result.images && result.images[0]" :src="result.images[0].url" />
                                    </td>
                                    <td>
                                        {{result.name}} <br />
                                        <strong style="font-size: 18px">{{moneyFormat(result.sellingMode.price.amount)}}</strong>
                                    </td>
                                    <td><a target="_blank" class="btn btn-outline-secondary" :href="`https://allegro.pl/oferta/${result.id}`" v-tooltip="'Otwórz na allegro'"><i class="fas fa-external-link-alt"></i></a></td>
                                </tr>
                                <tr v-show="allegro.additionalItemsLoading">
                                    <td colspan="3">
                                        <Loading />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from "../../../components/Loading";
import Pagination from "../../../components/Pagination";
import { moneyFormat } from "../../../helpers/utils";
import CustomerForm from "../../customers/components/CustomerForm";
import DateHoursInput from "../../../components/DateHoursInput";

export default {
    name: "OrderForm",
    components: {
        DateHoursInput,
        Loading,
        Pagination,
        CustomerForm
    },
    data() {
        return {
            customerVehiclesSearchQuery: '',
            customersVehiclesLoading: false,
            customersVehiclesPaginationData: {},
            editedPosition: {
                name: '',
                type: null,
                price: '',
                quantity: 1
            },
            editedPositionModalMode: null,
            availableTypes: [
                'natural_person',
                'company'
            ],
            allegro: {
                loading: false,
                limit: 15,
                results: [],
                additionalItemsLoading: false,
                resultsPage: 1,
                activePosition: {},
                searchingPhrase: ''
            },
            availableOrderStates: [],
            orderDateReceipt: null,
            orderHoursDateReceipt: null,
            orderMinutesDateReceipt: null,
            responsiblePersonSearchQuery: null,
            responsiblePersonLoading: false,
            responsiblePeople: [],
            responsiblePeoplePaginationData: {},
        }
    },
    props: {
        order: {
            type: Object,
            required: true,
            default: () => {
                return {
                    vehicle: {
                        mark: '',
                        model: '',
                        registration_number: ''
                    },
                    name: '',
                    positions: [],
                    date_receipt_vehicle: null,
                    date_delivery_vehicle: null,
                    responsible_person: null,
                    responsible_person_id: null
                }
            }
        },
        errorFields: {
            type: Object,
            required: false,
            default: () => {}
        }
    },
    computed: {
        fieldHasError() {
            return field => this.errorFields.hasOwnProperty(field)
        },
        fieldInvalidFeedback() {
            return field => {
                if(!this.fieldHasError(field)) return '';

                let error = this.errorFields[field];

                if(Array.isArray(error)) {
                    error = error.join("<br />")
                }

                return error;
            }
        },
        customerVehicles() {
            return this.$store.getters.customerVehicles;
        },
        positionTotalSum() {
            return position => Number(position.price) * position.quantity
        },
        moneyFormat() {
            return value => moneyFormat(value);
        },
        orderPositionsServicesTotalSum() {
            return this.orderPositionsServices.reduce((curr, position) => curr + this.positionTotalSum(position), 0);
        },
        orderPositionsPartsTotalSum() {
            return this.orderPositionsParts.reduce((curr, position) => curr + this.positionTotalSum(position), 0);
        },
        orderPositions() {
            return this.order.positions.map((p, i) => Object.assign(p, {origIndexInArray: i}))
        },
        orderPositionsServices() {
            return this.orderPositions.filter(p => p.type === 'service');
        },
        orderPositionsParts() {
            return this.orderPositions.filter(p => p.type === 'part');
        },
        orderDateReceiptVehicle() {
            if(!this.orderDateReceipt) {
                return null;
            }

            const date = new Date(this.orderDateReceipt);
            date.setHours(this.orderHoursDateReceipt);
            date.setMinutes(this.orderMinutesDateReceipt);

            return `${date.getFullYear()}-${(date.getMonth() + 1)}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
        },
        formatStartDate() {
            if(!this.order.start_date) {
                return null;
            }

            const date = new Date(this.order.start_date);

            return `${date.getFullYear()}-${(date.getMonth() + 1)}-${date.getDate()}`;
        }
    },
    mounted() {
        $("#editedPositionModal").on('hidden.bs.modal', () => {
            this.editedPosition = {
                name: '',
                type: null,
                price: '',
                quantity: 1
            };
            this.editedPositionModalMode = null;
        });
        this.setLoading(true);
        this.$api.get("/api/orders/getAvailableOrderStates").then(res => {
            this.availableOrderStates = res.data.data.states;
            this.setLoading(false);
        })
    },
    methods: {
        openCustomerVehiclesModal() {
            $("#customerVehiclesModal").modal("show");
            this.loadCustomersVehicles();
        },
        openEditPositionModal() {
            $("#editedPositionModal").modal("show");
        },
        openAllegroOrderPosResultsModal() {
            $("#allegroOrderPosResults").modal("show");
        },
        hideEditPositionModal() {
            $("#editedPositionModal").modal("hide");
        },
        openCustomerDataModal() {
            $("#customerDataModal").modal("show");
        },
        hideAllegroOrderPosResultsModal() {
            $("#allegroOrderPosResults").modal("hide");
        },
        loadCustomersVehicles() {
            this.customersVehiclesLoading = true;
            this.$api.get("/api/vehicles", {params: {query: this.customerVehiclesSearchQuery, page: this.customersVehiclesPaginationData.current_page}}).then(res => {
                this.$store.commit("setCustomerVehicles", res.data.data.vehicles);
                this.customersVehiclesLoading = false;
                this.customersVehiclesPaginationData = res.data.data.pagination;
            })
        },
        searchCustomerVehiclesByQuery(e) {
            if(e instanceof KeyboardEvent && e.code !== 'Enter') {
                return;
            }

            this.loadCustomersVehicles();
        },
        selectCustomerVehicle(vehicle) {
            this.order.vehicle = vehicle;
            this.order.vehicle_id = vehicle.id;
            this.order.customer = vehicle.customer;
            $("#customerVehiclesModal").modal("hide");
        },
        submitEditPositionModal() {
            if(this.editedPositionModalMode === 'create') {
                this.saveNewPosition();
            } else if(this.editedPositionModalMode === 'edit') {
                this.saveEditedPosition();
            }
        },
        saveNewPosition() {
            this.$emit('saveNewPosition', {
                newPosition: this.editedPosition,
                done: () => {
                    this.editedPosition = {
                        type: null,
                        name: '',
                        price: '',
                        quantity: 1
                    };
                    this.hideEditPositionModal();
                }
            })
        },
        saveEditedPosition() {
            this.$emit('saveEditedPosition', {
                position: this.editedPosition,
                done: () => {
                    this.editedPosition = {
                        name: '',
                        type: null,
                        price: '',
                        quantity: 1
                    };
                    this.hideEditPositionModal();
                }
            })
        },
        removePosition(position) {
            this.$emit('removePosition', position);
        },
        editPosition(position, indexInArray) {
            this.editedPosition = Object.assign({}, position, {indexInArray: indexInArray});
            this.editedPositionModalMode = 'edit';
            this.openEditPositionModal();
        },
        createNewPosition(type) {
            this.editedPositionModalMode = 'create';
            this.editedPosition.type = type;
            this.openEditPositionModal();
        },
        copyPosition(position) {
            this.$emit('copyPosition', position);
        },
        searchOrderPosOnAllegro(position) {
            this.allegro.loading = true;
            this.allegro.results = [];
            this.allegro.resultsPage = 1;
            this.allegro.activePosition = position;
            this.allegro.searchingPhrase = this.allegro.activePosition.name+' '+this.order.vehicle.name;
            this.openAllegroOrderPosResultsModal();
            this.getResultsFromAllegro()
                .catch(() => {
                    this.hideAllegroOrderPosResultsModal();
                })
            this.onAllegroResultsModalScroll();
        },
        getResultsFromAllegro() {
            return this.$api.get(`/api/allegro/api/search/?phrase=${this.allegro.searchingPhrase}&offset=${(this.allegro.resultsPage - 1)*this.allegro.limit}&limit=${this.allegro.limit}`)
                .then(res => {
                    this.allegro.results.push(...res.data.data.items);
                    this.allegro.loading = false;
                    this.allegro.additionalItemsLoading = false;
                    this.allegro.searchingPhrase = res.data.data.searchingPhrase;
                })
        },
        onAllegroResultsModalScroll(e) {
            if(!e || !e.target) {
                return;
            }

            const {target} = e;
            if(Math.ceil(target.scrollTop) >= target.scrollHeight - target.offsetHeight) {
                this.allegro.additionalItemsLoading = true;
                this.allegro.resultsPage++;
                this.getResultsFromAllegro()
            }
        },
        openResponsiblePeopleModal() {
            $("#responsiblePeopleModal").modal("show");
            this.loadResponsiblePeople();
        },
        loadResponsiblePeople() {
            this.responsiblePersonLoading = true;
            this.$api.get("/api/mechanics", {params: {query: this.responsiblePersonSearchQuery, page: this.customersVehiclesPaginationData.current_page}}).then(res => {
                this.responsiblePersonLoading = false;
                this.responsiblePeople = res.data.data.mechanics;
                this.responsiblePeoplePaginationData = res.data.data.pagination;
            })
        },
        searchResponsiblePerson(e) {
            if(e instanceof KeyboardEvent && e.code !== 'Enter') {
                return;
            }

            this.loadResponsiblePeople();
        },
        selectResponsiblePerson(person) {
            this.order.responsible_person = person;
            this.order.responsible_person_id = person.id;
            $("#responsiblePeopleModal").modal("hide");
        }
    },
    watch: {
        orderDateReceipt() {
            this.order.date_receipt_vehicle = this.orderDateReceiptVehicle;
        },
        orderHoursDateReceipt() {
            this.order.date_receipt_vehicle = this.orderDateReceiptVehicle;
        },
        orderMinutesDateReceipt() {
            this.order.date_receipt_vehicle = this.orderDateReceiptVehicle;
        }
    }
}
</script>

<style lang="scss">
.minutes-hours-col {
    width: 170px;

    .input-group-append {
        margin-right: -1px;
    }
}
</style>
