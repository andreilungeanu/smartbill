<?php

use AndreiLungeanu\Smartbill\Endpoints\InvoicesEndpoint;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

beforeEach(function () {
    $this->client = mock(PendingRequest::class);
    $this->endpoint = new InvoicesEndpoint($this->client);
});

it('can create an invoice', function () {
    // Arrange
    $invoiceData = ['client' => ['name' => 'Test Client']];
    $this->client
        ->shouldReceive('post')
        ->once()
        ->with('/invoice', $invoiceData)
        ->andReturn(
            mock(Response::class)
                ->shouldReceive('throw')
                ->andReturnSelf()
                ->getMock()
                ->shouldReceive('json')
                ->andReturn(['number' => '123'])
                ->getMock()
        );

    // Act
    $response = $this->endpoint->create($invoiceData);

    // Assert
    expect($response['number'])->toBe('123');
});

it('can create a v2 invoice', function () {
    // Arrange
    $invoiceData = ['value' => 100];
    $this->client
        ->shouldReceive('post')
        ->once()
        ->with('/invoice/v2', $invoiceData)
        ->andReturn(
            mock(Response::class)
                ->shouldReceive('throw')
                ->andReturnSelf()
                ->getMock()
                ->shouldReceive('json')
                ->andReturn(['series' => 'TESTV2'])
                ->getMock()
        );

    // Act
    $response = $this->endpoint->createV2($invoiceData);

    // Assert
    expect($response['series'])->toBe('TESTV2');
});

it('can get an invoice pdf', function () {
    // Arrange
    $this->client
        ->shouldReceive('get')
        ->once()
        ->with('/invoice/pdf', [
            'cif' => 'test-cif',
            'seriesname' => 'test-series',
            'number' => '123',
        ])
        ->andReturn(
            mock(Response::class)
                ->shouldReceive('throw')
                ->andReturnSelf()
                ->getMock()
                ->shouldReceive('body')
                ->andReturn('PDF Content')
                ->getMock()
        );

    // Act
    $response = $this->endpoint->getPdf('test-cif', 'test-series', '123');

    // Assert
    expect($response)->toBe('PDF Content');
});
