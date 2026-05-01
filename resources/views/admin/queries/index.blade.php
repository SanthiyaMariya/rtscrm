<!-- resources/views/employee/queries/index.blade.php -->
@extends('layouts.app')  // Or your actual layout file

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2>Assigned Query Details</h2>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Project
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Query Details
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Assigned Date
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Start Date
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    End Date
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Duration
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($queries as $query)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $query->id }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $query->project->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $query->title }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $query->query_details }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $query->assigned_date }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $query->status }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $query->start_date ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $query->end_date ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $query->duration ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <td><strong>{{ $q->hours_spent ?? 0 }} Hrs</strong></td> 
                                  
                                    <!-- Update Button -->
                                    <form action="{{ route('employee.queries.update', $query->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status">
                                          <option value="Pending" {{ $query->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                          <option value="In Progress" {{ $query->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                          <option value="Completed" {{ $query->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        <button type="submit" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" >
                                            Update
                                        </button>
                                    </form>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    @endsection